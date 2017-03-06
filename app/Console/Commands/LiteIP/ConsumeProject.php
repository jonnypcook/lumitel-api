<?php

namespace App\Console\Commands\LiteIP;

use App\Repositories\LiteipRepository;
use App\Repositories\OwnerRepository;
use App\Services\IoT\IotDiscoverable;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Validator;

class ConsumeProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'liteip:consume-project {ownerId} {projectIds*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume LiteIP project endpoint into existing installation';


    /**
     * @var Discover
     */
    private $discover;

    /**
     * ConsumeProject constructor.
     * @param Discover $discover
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
    public function handle(OwnerRepository $ownerRepository, LiteipRepository $liteipRepository)
    {
        // validate parameters
        $validator = Validator::make($this->arguments(), [
            'ownerId' => 'bail|required|numeric|min:1',
            'projectIds' => 'bail|required|numericArray'
        ]);


        // check parameters
        if ($validator->fails()) {
            $errors = array();
            foreach ($validator->errors()->messages() as $field => $errors) {
                foreach ($errors as $error) {
                    $errors[] = $error;
                }
            }

            $this->errorAndExit($errors);
        }

        // check owner
        $this->info('checking owner', OutputInterface::VERBOSITY_VERBOSE);
        $owner = $ownerRepository->find($this->argument('ownerId'));
        if (!$owner) {
            $this->errorAndExit('Owner does not exist');
        }

        // check to see if projects have been defined
        $projectIds = $this->argument('projectIds');
        $this->info('checking project ids', OutputInterface::VERBOSITY_VERBOSE);

        $liteipRepository->setCacheLifetime(0); // temporary cache memory loss
        $existingLiteip = $liteipRepository->findWhereIn(['vendor_id', $projectIds]);
        if (count($existingLiteip)) {
            $errors= array();
            foreach ($existingLiteip as $liteip) {
                $errors[] = $liteip->vendor_id . ' already assigned to an installation' . $liteip->liteip_id;

                // TODO: REMOVE BELOW!!!
                $liteipRepository->delete($liteip->liteip_id);
            }

            $this->errorAndExit($errors);
        }

        $this->info('starting project consumption', OutputInterface::VERBOSITY_VERBOSE);
        $this->discover->setThrowExceptions(true);
        $this->discover->consumeProject($projectIds, $owner);

    }


    /**
     * display errors and exit
     * @param $errors
     */
    public function errorAndExit($errors) {
        $this->error('Command failed with the following errors:');
        foreach ((array)$errors as $error) {
            $this->error($error);
        }

        exit;
    }

}
