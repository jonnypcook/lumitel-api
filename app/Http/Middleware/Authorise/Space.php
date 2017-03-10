<?php
/**
 * Created by PhpStorm.
 * User: jonathancook
 * Date: 10/03/2017
 * Time: 14:54
 */

namespace App\Http\Middleware\Authorise;


use App\Repositories\SpaceRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

trait Space
{
    /**
     * @param $spaceId
     * @return \App\Models\Space
     * @throws AuthorizationException
     * @throws ModelNotFoundException
     */
    protected function authoriseSpace($spaceId) {
        if (!$spaceId) {
            throw new ModelNotFoundException();
        }

        if (!preg_match('/^[\d]+$/', $spaceId)) {
            throw new BadRequestHttpException('spaceId should be an integer.');
        }

        $spaceRepository = new SpaceRepository();
        $space = $spaceRepository->with(['installation'])->find($spaceId);

        if (!$space) {
            throw new ModelNotFoundException();
        }

        //If access not granted
        if ($space->installation->hasUser(\Auth::user()) !== true) {
            throw new AuthorizationException('Permission to space resource not granted');
        }

        return $space;
    }
}