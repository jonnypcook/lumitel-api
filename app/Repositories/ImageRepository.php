<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class ImageRepository extends EloquentRepository implements ImageRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.image';
    protected $model = 'App\Models\Image';
}