<?php

namespace App\Console\Commands\LiteIP;

use App\Console\Commands\ConsoleErrors;
use App\Models\Installation;
use App\Models\Owner;
use App\Repositories\InstallationRepository;
use App\Repositories\LiteipRepository;
use App\Repositories\OwnerRepository;
use App\Repositories\SpaceRepository;
use App\Services\IoT\IotDiscoverable;
use App\Services\IoT\Lightwave\Discover;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Validator;

class Consume extends Command
{
    use ConsoleErrors;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'liteip:consume {ownerId} {installationId} {postcode} {--nomap}';

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
    public function handle(OwnerRepository $ownerRepository, InstallationRepository $installationRepository,
                           LiteipRepository $liteipRepository, SpaceRepository $spaceRepository)
    {
        try {
            $this->comment('Starting LiteIP discovery and consume process');

            // validate parameters
            $validator = Validator::make($this->arguments(), [
                'ownerId' => 'bail|required|numeric|min:1',
                'installationId' => 'bail|required|numeric|min:1',
                'postcode' => 'bail|required|regex:/^[a-z]{1}[\da-z _]+$/i',
            ]);


            // check parameters
            if ($validator->fails()) {
                $this->errorAndExit($validator->errors()->messages());
            }

            // check owner
            $this->comment('Checking owner ...', OutputInterface::VERBOSITY_VERBOSE);
            $owner = $ownerRepository->find($this->argument('ownerId'));
            if (!$owner) {
                $this->errorAndExit('Owner does not exist');
            }

            // check that installation exists
            $this->comment('Checking installation ...', OutputInterface::VERBOSITY_VERBOSE);
            $installation = $installationRepository->find($this->argument('installationId'));
            if (!$installation) {
                $this->errorAndExit('Installation does not exist');
            }

            // check installation belongs to owner
            if ($installation->owner->owner_id != $owner->owner_id) {
                $this->errorAndExit('Installation is not linked to owner');
            }

            // check that project exists
            $project = $this->discover->findProject(strtoupper(preg_replace('/[ ]/', '_', $this->argument('postcode'))));

            // check that project is not already assigned
            $liteipRepository->setCacheLifetime(0); // temporary cache memory loss
            $lipConnection = $liteipRepository->findBy('vendor_id', $project->ProjectID);
            if ($lipConnection) {
                if ($lipConnection->iotSource->installation->installation_id !== (int)$this->argument('installationId')) {
                    $this->errorAndExit(sprintf('The liteIP project #%d (postcode=%s) has already been consumed into a different ' .
                        'installation (installation id=%d)', $project->ProjectID, $lipConnection->postcode,
                        $lipConnection->iotSource->installation->installation_id));
                }

                $this->warn(sprintf('The liteIP project #%d (postcode=%s) already consumed', $project->ProjectID, $lipConnection->postcode));
            }

            // find drawings and check that they are not already assigned
            $this->comment(sprintf('Requesting drawings for project %s', $project->ProjectDescription), OutputInterface::VERBOSITY_VERBOSE);
            $drawings = $this->discover->findDrawings($project->ProjectID);

            $drawingIds = array();
            foreach ($drawings as $drawing) {
                $drawingIds[$drawing->DrawingID] = $drawing->DrawingID . '=' .preg_replace('/([.][^.]+)+$/', '', $drawing->Drawing);
            }
            asort($drawingIds);

            // validate drawings
            $this->comment(sprintf('Validating %d drawings ...', count($drawingIds)), OutputInterface::VERBOSITY_VERBOSE);
            $this->validateDrawings($spaceRepository, $installation, $drawingIds);

            $discoverSetup = array('project' => $project, 'drawings' => $drawings);

            // find mappings where applicable
            if (empty($this->option('nomap'))) {
                $this->comment('Entering mapping configuration mode ...', OutputInterface::VERBOSITY_VERBOSE);
                $discoverSetup['mappings'] = $this->findMappings($spaceRepository, $installation, $drawingIds);
            }

            $this->comment('Consuming device data from liteIP gateway ...', OutputInterface::VERBOSITY_VERBOSE);
            $results = $this->discover->discover($owner, $installation, $discoverSetup);

            $this->info(sprintf('Inserted %d devices, Updated %d devices', $results['added'], $results['updated']));
        } catch (\Exception $e) {
            $this->errorAndExit($e->getMessage());
        }

    }


    /**
     * discover desired drawing/space mappings
     * @param SpaceRepository $spaceRepository
     * @param Installation $installation
     * @param array $drawings
     * @return array
     */
    private function findMappings(SpaceRepository $spaceRepository, Installation $installation, array $drawings) {
        $mappings = array();
        $rootSpace = $spaceRepository->setCacheLifetime(0)
            ->where('parent_id', '=')
            ->where('installation_id', '=', $installation->installation_id)
            ->findAll();

        if ($rootSpace->isEmpty()) {
            return $mappings;
        }

        $rootSpace = $rootSpace->first();

        $existingSpaces = $spaceRepository
            ->setCacheLifetime(0)
            ->where('parent_id', '=', $rootSpace->space_id)
            ->where('installation_id', '=', $installation->installation_id)
            ->whereIn('vendor_id', array_keys($drawings))
            ->findAll();

        if ($existingSpaces->isNotEmpty()) {
            foreach ($existingSpaces as $existingSpace) {
                unset($drawings[$existingSpace->vendor_id]);
            }
        }


        $spaces = $spaceRepository
            ->setCacheLifetime(0)
            ->where('parent_id', '=', $rootSpace->space_id)
            ->where('installation_id', '=', $installation->installation_id)
            ->where('vendor_id', '=')
            ->findAll();

        if ($spaces->isNotEmpty()) {
            foreach ($spaces as $space) {
                $availableDrawings = implode(", ", $drawings);
                while (true) {
                    $drawingID = $this->ask("Please select id of drawing that you would like to assign to space '" .
                        $space->name . "':\r\n" . $availableDrawings . "\r\nNote: Press '0' to skip");

                    if (!preg_match('/^[\d]+$/', $drawingID)) {
                        continue;
                    }

                    if ($drawingID == 0) {
                        break;
                    }

                    if(isset($drawings[$drawingID])) {
                        $mappings[$drawingID] = $space->space_id;
                        unset($drawings[$drawingID]);
                        break;
                    }

                }
            }
        }

        return $mappings;
    }

    /**
     * @param array $drawings
     * @param SpaceRepository $spaceRepository
     * @param Installation $installation
     */
    private function validateDrawings(SpaceRepository $spaceRepository, Installation $installation, array $drawings) {
        $spaces = $spaceRepository
            ->setCacheLifetime(0)
            ->where('installation_id', '!=', $installation->installation_id)
            ->whereIn('vendor_id', array_keys($drawings))
            ->findAll();

        if ($spaces->isNotEmpty()) {
            $errors = array();
            foreach ($spaces as $space) {
                $errors[] = sprintf('Drawing #%d is already assigned to installation #%d on space %s (%d)',
                    $space->vendor_id, $space->installation_id, $space->space_id);
            }
            $this->errorAndExit($errors);
        }
    }



}
