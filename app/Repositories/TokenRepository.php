<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class TokenRepository extends EloquentRepository implements UserRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.token';
    protected $model = 'App\Models\Token';
}