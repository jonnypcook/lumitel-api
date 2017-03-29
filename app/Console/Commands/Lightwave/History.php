<?php

namespace App\Console\Commands\Lightwave;

use App\Console\Commands\ConsoleErrors;
use App\Models\Device;
use App\Models\DeviceLightWave;
use App\Models\Telemetry;
use App\Repositories\DeviceHistoryRepository;
use App\Repositories\DeviceLightWaveRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\TelemetryRepository;
use App\Services\IoT\IotDataQueryable;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Validator;

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
     * @var IotDataQueryable
     */
    protected $dataQueryApi;

    /**
     * Create a new command instance.
     */
    public function __construct(IotDataQueryable $iotDataQueryable)
    {
        $this->dataQueryApi = $iotDataQueryable;
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

        $pageNumber = 1;
        $resultsPerPage = 500;
        $this->dataQueryApi->setFrom(new \DateTime($this->argument('dateFrom') . ' 00:00:00'));
        $this->dataQueryApi->setTo(new \DateTime($this->argument('dateTo') . ' 23:59:59'));
        $this->dataQueryApi->setResultsPerPage($resultsPerPage);
        $this->dataQueryApi->setPageNumber($pageNumber);

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
                $pageNumber = 1;
                $this->dataQueryApi->setPageNumber(1);
                $this->info(sprintf('Processing %s data on device "%s", id #%d, vendor id #%d', $telemetry->name, $device->label, $device->device_id, $vendorId), OutputInterface::VERBOSITY_VERBOSE);
                $data = $this->dataQueryApi->getDeviceData($device, $telemetry->name, true);

                if (empty($data['paginator'] || empty($data['paginator']['totalRecords'])) || ($data['paginator']['totalRecords'] == 0)) {
                    $this->warn(sprintf('No %s data returned for device', $telemetry->name),OutputInterface::VERBOSITY_VERBOSE);
                    continue;
                }

                $this->saveHistoryData($data['rows'], $device, $telemetry);

                $totalRecords = (int)$data['paginator']['totalRecords'];
                $totalPages = ceil($totalRecords / $resultsPerPage);

                while ($pageNumber < $totalPages) {
                    $pageNumber += 1;
                    $this->info(sprintf('Processing %s data page %d', $telemetry->name, $pageNumber), OutputInterface::VERBOSITY_VERBOSE);

                    $this->dataQueryApi->setPageNumber($pageNumber);
                    $data = $this->dataQueryApi->getDeviceData($device, $telemetry->name, true);
                    $this->saveHistoryData($data['rows'], $device, $telemetry);
                }

                $this->info(sprintf('Added %d rows of %s history for device #%d', $totalRecords, $telemetry->name, $device->device_id), OutputInterface::VERBOSITY_VERBOSE);
            }


        }

        // check that owner exists
        $this->info('Finished');

    }

    protected function saveHistoryData(array $items, Device $device, Telemetry $telemetry) {
        $deviceHistoryRepository = new DeviceHistoryRepository();
        $deviceHistoryRepository->forgetCache();
        $deviceHistoryRepository->beginTransaction();
        foreach ($items as $item) {
            $dtUpdatedUtc = new \DateTime();
            $dtUpdatedUtc->setTimestamp(strtotime($item['utc_time']));
            $dtUpdatedLocal = new \DateTime();
            $dtUpdatedLocal->setTimestamp(strtotime($item['local_time']));
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
