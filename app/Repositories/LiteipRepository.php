<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class LiteipRepository extends EloquentRepository implements LiteipRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.liteip';
    protected $model = 'App\Models\Liteip';
}