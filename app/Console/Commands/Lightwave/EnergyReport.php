<?php

namespace App\Console\Commands\Lightwave;

use App\Console\Commands\ConsoleErrors;
use App\Repositories\DeviceLightWaveRepository;
use App\Repositories\DeviceRepository;
use Illuminate\Console\Command;
use Validator;
use File;

class EnergyReport extends Command
{
    use ConsoleErrors;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lightwave:energy-report {deviceId} {dateFrom} {dateTo} {dir}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Produce energy report for a device between 2 dates and save to csv file';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(DeviceLightWaveRepository $deviceLightWaveRepository)
    {
        $this->info('Starting Lightwave device energy data report');
        // validate parameters
        $validator = Validator::make($this->arguments(), [
            'deviceId' => 'bail|required|numeric|min:1',
            'dateFrom' => 'bail|required|date',
            'dateTo' => 'bail|required|date',
            'dir' => 'bail|required|string'
        ]);


        // check parameters
        if ($validator->fails()) {
            $this->errorAndExit($validator->errors()->messages());
        }

        $device = $deviceLightWaveRepository
            ->with(['device'])
            ->where('vendor_id', '=', $this->argument('deviceId'))
            ->findFirst();

        if (empty($device)) {
            $this->errorAndExit(sprintf('The device #%d is not a registered lightwaveRF product', $this->argument('deviceId')));
        }

        if (!is_dir($this->argument('dir'))) {
            $this->errorAndExit(sprintf('The directory "%s" is invalid', $this->argument('dir')));
        }


        $dtFrom = new \DateTime($this->argument('dateFrom'));
        $dtTo = new \DateTime($this->argument('dateTo'));

        $file = rtrim($this->argument('dir'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $device->vendor_id . ' ' .
            $dtFrom->format('Y-m-d H:i') . '-' . $dtTo->format('Y-m-d H:i') .'.csv';


        $dataService = app()->make('Lightwave\DataService');
        $dataService->setFrom($dtFrom);
        $dataService->setTo($dtTo);
        $dataService->setResultsPerPage(350);
        $data = $dataService->getData($device->vendor_id, 'energy', true);

        $paginator = $data['paginator'];
        $rows = $data['rows'];

        $crlf = chr(13) . chr(10);
        $contents = '"device", "date", "time", "current", "total"' . $crlf;

        foreach ($rows as $row) {
            $contents .= sprintf('"%s","%s","%s","%s","%s"',
                $device->device->label,
                date('Y-m-d', strtotime($row['utc_time'])),
                date('H:i:s', strtotime($row['utc_time'])),
                $row['current_use'],
                $row['total_day_use']
            ) . $crlf;
        }

        $bytesWritten = File::put($file, $contents);
        $this->info(sprintf('Written report to file %s (%d bytes)', $file, $bytesWritten));

        // check that owner exists
        $this->info('Finished');

    }


}
