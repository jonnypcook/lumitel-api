<?php

namespace App\Console\Commands\LiteIP;

use Illuminate\Console\Command;

class EmergencyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'liteip:emergency-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run emergency report against existing installation and devices.';

    /**
     * Create a new command instance.
     *
     * @return void
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
    public function handle()
    {
        //
    }
}
