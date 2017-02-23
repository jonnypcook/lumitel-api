<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class DeviceLiteIpStatusRepository extends EloquentRepository implements DeviceLiteIpStatusRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.device.liteip.status';
    protected $model = 'App\Models\DeviceLiteIpStatus';
}