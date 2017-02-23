<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class RoleRepository extends EloquentRepository implements RoleRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.role';
    protected $model = 'App\Models\Role';
}