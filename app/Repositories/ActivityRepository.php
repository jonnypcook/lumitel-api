<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class ActivityRepository extends EloquentRepository implements ActivityRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.activity';
    protected $model = 'App\Models\Activity';
}