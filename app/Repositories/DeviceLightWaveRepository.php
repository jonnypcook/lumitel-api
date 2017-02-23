<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class DeviceLightWaveRepository extends EloquentRepository implements DeviceLightWaveRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.device.lightwave';
    protected $model = 'App\Models\DeviceLightWave';
}