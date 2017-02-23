<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class DeviceTypeRepository extends EloquentRepository implements DeviceTypeRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.device.type';
    protected $model = 'App\Models\DeviceType';
}