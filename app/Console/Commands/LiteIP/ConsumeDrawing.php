<?php

namespace App\Console\Commands\LiteIP;

use Illuminate\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;

class ConsumeDrawing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'liteip:consume-drawing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume LiteIP drawing endpoint into given project/space';

    /**
     * ConsumeDrawing constructor.
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
        $this->info('Display this on the screen');
        $verbosityLevel = $this->getOutput()->getVerbosity();

        $this->info('verbosity level: ' . $verbosityLevel);
        $this->info('normal', OutputInterface::VERBOSITY_NORMAL);
        $this->info('debug',OutputInterface::VERBOSITY_DEBUG);
        $this->info('quiet', OutputInterface::VERBOSITY_QUIET);
        $this->info('verbose',OutputInterface::VERBOSITY_VERBOSE);
        $this->info('very verbose', OutputInterface::VERBOSITY_VERY_VERBOSE);
    }
}
