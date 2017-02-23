<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class SpaceRepository extends EloquentRepository implements SpaceRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.space';
    protected $model = 'App\Models\Space';
}