<?php
/**
 * Created by PhpStorm.
 * User: jonathancook
 * Date: 10/03/2017
 * Time: 14:54
 */

namespace App\Http\Middleware\Authorise;


use App\Repositories\InstallationRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

trait Installation
{
    /**
     * @param $installationId
     * @return mixed
     * @throws AuthorizationException
     * @throws ValidationException
     */
    protected function authoriseInstallation($installationId) {
        if (!$installationId) {
            throw new ModelNotFoundException();
        }

        if (!preg_match('/^[\d]+$/', $installationId)) {
            throw new BadRequestHttpException('installationId should be an integer.');
        }

        $installationRepository = new InstallationRepository();
        $installation = $installationRepository->with(['users', 'address'])->find($installationId);

        if (!$installation) {
            throw new ModelNotFoundException();
        }

        //If access not granted
        if ($installation->hasUser(\Auth::user()) !== true) {
            throw new AuthorizationException('Permission to installation resource not granted');
        }


        return $installation;
    }
}