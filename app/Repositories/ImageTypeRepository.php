<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class ImageTypeRepository extends EloquentRepository implements ImageTypeRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.image.type';
    protected $model = 'App\Models\ImageType';
}