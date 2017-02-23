<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class DeviceLiteipRepository extends EloquentRepository implements DeviceLiteipRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.device.liteip';
    protected $model = 'App\Models\DeviceLiteIp';
}