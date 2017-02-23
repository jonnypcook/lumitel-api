<?php
namespace App\Repositories;

use Rinvex\Repository\Repositories\EloquentRepository;

class InstallationRepository extends EloquentRepository implements InstallationRepositoryContract
{
    protected $repositoryId = 'rinvex.repository.installation';
    protected $model = 'App\Models\Installation';
}