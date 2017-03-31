<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class DeviceHistoryTransactionRepository extends EloquentRepository implements DeviceHistoryTransactionRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.device.history.transaction';
    protected $model = 'App\Models\DeviceHistoryTransaction';
}