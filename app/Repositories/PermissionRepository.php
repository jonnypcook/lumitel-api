<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class PermissionRepository extends EloquentRepository implements PermissionRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.permission';
    protected $model = 'App\Models\Permission';
}