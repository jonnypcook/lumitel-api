<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class CommandRepository extends EloquentRepository implements CommandRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.command';
    protected $model = 'App\Models\Command';
}