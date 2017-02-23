<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class LightwaveRepository extends EloquentRepository implements LightwaveRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.lightwave';
    protected $model = 'App\Models\Lightwave';
}