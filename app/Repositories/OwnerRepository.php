<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class OwnerRepository extends EloquentRepository implements OwnerRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.owner';
    protected $model = 'App\Models\Owner';
}