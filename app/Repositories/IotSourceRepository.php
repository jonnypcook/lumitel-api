<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class IotSourceRepository extends EloquentRepository implements IotSourceRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.iotsource';
    protected $model = 'App\Models\IotSource';
}