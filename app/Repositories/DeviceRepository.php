<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class DeviceRepository extends EloquentRepository implements DeviceRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.device';
    protected $model = 'App\Models\Device';
}