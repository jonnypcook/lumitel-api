<?php

namespace App\Console\Commands\Lightwave;

use App\Models\Installation;
use App\Models\Lightwave;
use App\Models\Owner;
use App\Repositories\DeviceLightWaveRepository;
use App\Repositories\InstallationRepository;
use App\Repositories\IotSourceRepository;
use App\Repositories\LightwaveRepository;
use App\Repositories\OwnerRepository;
use App\Repositories\SpaceRepository;
use App\Services\IoT\IotDiscoverable;
use App\Services\IoT\Lightwave\Data;
use App\Services\IoT\Lightwave\Discover;
use App\Services\IoT\Lightwave\Command\Light;
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
    protected $signature = 'lightwave:consume {ownerId} {installationId} {lwUser}';

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
    public function handle(OwnerRepository $ownerRepository, InstallationRepository $installationRepository, LightwaveRepository $lightwaveRepository)
    {
        $this->info('Starting Lightwave discovery and consume process');
        // validate parameters
        $validator = Validator::make($this->arguments(), [
            'ownerId' => 'bail|required|numeric|min:1',
            'installationId' => 'bail|required|numeric|min:1',
            'lwUser' => 'bail|required|email'
        ]);


        // check parameters
        if ($validator->fails()) {
            $this->errorAndExit($validator->errors()->messages());
        }

        // check that owner exists
        $this->info('checking owner (' . $this->argument('ownerId') . ')', OutputInterface::VERBOSITY_VERBOSE);
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

        // check that lwUser is not already consumer by an alternative Installation
        $lightwaveRepository->setCacheLifetime(0); // temporary cache memory loss
        $lwConnection = $lightwaveRepository->findBy('email', $this->argument('lwUser'));
        if ($lwConnection) {
            if ($lwConnection->iotSource->installation->installation_id !== (int)$this->argument('installationId')) {
                $this->errorAndExit(sprintf('The lightwave user %s has already been consumed into a different installation (installation id=%d)', $this->argument('lwUser'), $lwConnection->iotSource->installation->installation_id));
            }
            $this->warn(sprintf('The lightwave user %s has already been consumed into this installation', $this->argument('lwUser')));
        }

        $this->info('consuming devices into installation', OutputInterface::VERBOSITY_VERBOSE);
        $this->discover->discover($owner, $installation, $this->argument('lwUser'));

        $this->info('Finished');
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
