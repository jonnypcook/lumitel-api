<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class ActivityTypeRepository extends EloquentRepository implements ActivityTypeRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.activity.type';
    protected $model = 'App\Models\ActivityType';
}