<?php

namespace App\Console\Commands\Lightwave;

use App\Console\Commands\ConsoleErrors;
use App\Models\Device;
use App\Models\DeviceHistoryTransaction;
use App\Models\DeviceLightWave;
use App\Models\Telemetry;
use App\Repositories\DeviceHistoryRepository;
use App\Repositories\DeviceHistoryTransactionRepository;
use App\Repositories\DeviceLightWaveRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\TelemetryRepository;
use App\Services\IoT\IotDataQueryable;
use Faker\Provider\cs_CZ\DateTime;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Validator;
use DB;

class History extends Command
{
    use ConsoleErrors;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lightwave:history {dateFrom} {dateTo} {deviceId*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Historic data consumption process for Lightwave devices';


    /**
     * @var \App\Services\IoT\Lightwave\Data
     */
    protected $dataQueryApi;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        $this->dataQueryApi = resolve('Lightwave\DataService');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(DeviceRepository $deviceRepository)
    {
        $this->info('Starting Lightwave device history consumption process');

        // validate parameters
        $validator = Validator::make($this->arguments(), [
            'dateFrom' => 'bail|required|date_format:Y-m-d',
            'dateTo' => 'bail|required|date_format:Y-m-d',
            'deviceId' => 'bail|required|numericArray',
        ]);

        // check parameters
        if ($validator->fails()) {
            $this->errorAndExit($validator->errors()->messages());
        }

        $dtFrom = new \DateTime($this->argument('dateFrom') . ' 00:00:00');
        $dtTo = new \DateTime($this->argument('dateTo') . ' 23:59:59');

        $this->dataQueryApi->setFrom($dtFrom);
        $this->dataQueryApi->setTo($dtTo);
        $this->dataQueryApi->setResultsPerPage(500);

        $lastDate = new \DateTime($this->argument('dateTo') . ' 23:59:59');
        $lastDate->modify('+1 hour');
        $lastReading = 0;

        $devices = $deviceRepository
            ->whereIn('device_id', $this->argument('deviceId'))
            ->where('provider_type', '=', DeviceLightWave::class)
            ->with(['provider', 'deviceType'])
            ->findAll();

        if ($devices->isEmpty()) {
            $this->info('No devices found in database', OutputInterface::VERBOSITY_VERBOSE);
        }

        foreach ($devices as $device) {
            $vendorId = $device->provider->vendor_id;
            foreach ($device->deviceType->telemetry as $telemetry) {
                if (!$this->validateHistoryTransaction($device->device_id, $telemetry->telemetry_id, $dtFrom, $dtTo)) {
                    $this->error(sprintf('Already uploaded %s data for device "%s", id #%d, vendor id #%d', $telemetry->name, $device->label, $device->device_id, $vendorId));
                    continue;
                }

                $createdEntity = $this->createHistoryTransaction($device->device_id, $telemetry->telemetry_id, $dtFrom, $dtTo);

                $this->dataQueryApi->setDevice($device);
                $this->dataQueryApi->setType($telemetry->name);

                foreach ($this->dataQueryApi as $page => $history) {
                    $this->info(sprintf('Processing page %d for device #%d', $page, $device->device_id), OutputInterface::VERBOSITY_VERBOSE);
                    $this->saveHistoryData($history, $device, $telemetry, $lastDate, $lastReading);
                }


                $this->info(sprintf('Added %d rows of %s history for device #%d', $this->dataQueryApi->getTotalResults(), $telemetry->name, $device->device_id), OutputInterface::VERBOSITY_VERBOSE);
                $createdEntity->completed_at = new \DateTime();
                $createdEntity->save();
            }


        }

        // check that owner exists
        $this->info('Finished');

    }

    /**
     * @param $deviceId
     * @param $telemetryId
     * @param \DateTime $from
     * @param \DateTime $to
     * @return DeviceHistoryTransaction
     */
    protected function createHistoryTransaction($deviceId, $telemetryId, \DateTime $from, \DateTime $to) {
        $deviceHistoryTransactionRepository = new DeviceHistoryTransactionRepository();
        $createdEntity = $deviceHistoryTransactionRepository->create([
            'device_id' => $deviceId,
            'telemetry_id' => $telemetryId,
            'from' => $from,
            'to' => $to,
        ]);
        return  $createdEntity;
    }

    /**
     * @param $deviceId
     * @param $telemetryId
     * @param \DateTime $from
     * @param \DateTime $to
     * @return bool
     */
    protected function validateHistoryTransaction($deviceId, $telemetryId, \DateTime $from, \DateTime $to) {
        $results = DB::select(DB::raw("SELECT * FROM device_history_transaction WHERE " .
            "`device_id` = {$deviceId} AND " .
            "`telemetry_id` = {$telemetryId} AND " .
            "((`from` <= '{$from->format('Y-m-d H:i:s')}' AND `to` >= '{$from->format('Y-m-d H:i:s')}') OR" .
            "(`from` <= '{$to->format('Y-m-d H:i:s')}' AND `to` >= '{$to->format('Y-m-d H:i:s')}'))"
        ));

        return empty($results);
    }

    /**
     * @param array $items
     * @param Device $device
     * @param Telemetry $telemetry
     * @param \DateTime $lastValidDate
     * @param $lastReading
     */
    protected function saveHistoryData(array $items, Device $device, Telemetry $telemetry, \DateTime &$lastValidDate, &$lastReading) {
        $deviceHistoryRepository = new DeviceHistoryRepository();
        $deviceHistoryRepository->forgetCache();
        $deviceHistoryRepository->beginTransaction();
        foreach ($items as $item) {
            $dtUpdatedUtc = new \DateTime();
            $dtUpdatedUtc->setTimestamp(strtotime($item['utc_time']));
            $dtUpdatedLocal = new \DateTime();
            $dtUpdatedLocal->setTimestamp(strtotime($item['local_time']));

            $timeDiff = (($lastValidDate->getTimestamp() - $dtUpdatedLocal->getTimestamp()) / 60);
            if ($timeDiff < 5) {
                if ((abs($item['current_use'] - $lastReading) < 100)) {
                    continue;
                }
            }

            $lastValidDate = $dtUpdatedLocal;
            $lastReading = $item['current_use'];
            $deviceHistoryRepository->create([
                'device_id' => $device->device_id,
                'telemetry_id' => $telemetry->telemetry_id,
                'reading_current' => $item['current_use'],
                'reading_day' => $item['total_day_use'],
                'utc_created_at' => $dtUpdatedUtc,
                'local_created_at' => $dtUpdatedLocal,
            ]);
        }
        $deviceHistoryRepository->commit();
    }
}
