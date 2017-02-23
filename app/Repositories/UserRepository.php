<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class UserRepository extends EloquentRepository implements UserRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.user';
    protected $model = 'App\Models\User';
}