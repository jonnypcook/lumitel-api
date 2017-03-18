<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class TelemetryRepository extends EloquentRepository implements TelemetryRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.telemetry';
    protected $model = 'App\Models\Telemetry';
}