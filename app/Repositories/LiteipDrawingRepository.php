<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class LiteipDrawingRepository extends EloquentRepository implements LiteipDrawingRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.liteip.drawing';
    protected $model = 'App\Models\LiteIpDrawing';
}