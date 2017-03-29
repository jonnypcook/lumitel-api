<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class DeviceHistoryRepository extends EloquentRepository implements DeviceHistoryRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.device.history';
    protected $model = 'App\Models\DeviceHistory';
}