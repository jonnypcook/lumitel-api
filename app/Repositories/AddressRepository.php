<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class AddressRepository extends EloquentRepository implements AddressRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.address';
    protected $model = 'App\Models\Address';
}