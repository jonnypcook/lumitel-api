<?php

namespace App\Console\Commands\LiteIP;

use App\Models\Installation;
use App\Models\Owner;
use App\Repositories\InstallationRepository;
use App\Repositories\LiteipRepository;
use App\Repositories\OwnerRepository;
use App\Services\IoT\IotDiscoverable;
use App\Services\IoT\Lightwave\Discover;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Validator;

class Consume extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'liteip:consume {ownerId} {installationId} {lipProjectId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Discover and consume Lightwave project endpoint into existing installation';


    /**
     * @var Discover
     */
    private $discover;

    /**
     * ConsumeProject constructor.
     */
    public function __construct(IotDiscoverable $discover)
    {
        $this->discover = $discover;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(OwnerRepository $ownerRepository, InstallationRepository $installationRepository, LiteipRepository $liteipRepository)
    {
        // validate parameters
        $validator = Validator::make($this->arguments(), [
            'ownerId' => 'bail|required|numeric|min:1',
            'installationId' => 'bail|required|numeric|min:1',
            'lipProjectId' => 'bail|required|numeric|min:1',
        ]);


        // check parameters
        if ($validator->fails()) {
            $this->errorAndExit($validator->errors()->messages());
        }

        // check owner
        $this->info('checking owner', OutputInterface::VERBOSITY_VERBOSE);
        $owner = $ownerRepository->find($this->argument('ownerId'));
        if (!$owner) {
            $this->errorAndExit('Owner does not exist');
        }

        // check that installation exists
        $this->info('checking installation (id=' . $this->argument('installationId'). ')', OutputInterface::VERBOSITY_VERBOSE);
        $installation = $installationRepository->find($this->argument('installationId'));
        if (!$installation) {
            $this->errorAndExit('Installation does not exist');
        }

        // check installation belongs to owner
        if ($installation->owner->owner_id != $owner->owner_id) {
            $this->errorAndExit('Installation is not linked to owner');
        }

//        $this->comment($this->discover->discover(new Owner(), new Installation(), array()));
        $this->comment('Completed');

    }


    /**
     * display errors and exit
     * @param $errors
     */
    public function errorAndExit($errors) {
        $this->comment('Command failed with the following errors:');
        foreach ((array)$errors as $error) {
            if (is_array($error)) {
                foreach ($error as $item) {
                    $this->error($item);
                }

            } else {
                $this->error($error);
            }
        }

        exit;
    }

}
