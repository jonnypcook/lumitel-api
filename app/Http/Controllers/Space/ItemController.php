<?php

namespace App\Http\Controllers\Space;

use App\Http\Requests\SpaceList;
use App\Models\Space;
use App\Repositories\SpaceRepository;
use App\Transformer\SpaceTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ItemController extends Controller
{
    /**
     * @param Request $request
     * @param SpaceList $spaceList
     * @param SpaceRepository $spaceRepository
     * @return mixed
     * @throws \Exception
     */
    public function index(Request $request, SpaceList $spaceList, SpaceRepository $spaceRepository)
    {
        $ignoreRoot = $request->get('ignoreRoot', false);
        $parentId = $request->get('parentId', false);
        $installationId = $request->get('installationId', false);

        if (($ignoreRoot !== false) && ($parentId === false)) {
            $spaceRepositoryRoot = new SpaceRepository();
            $rootSpace = $spaceRepositoryRoot->where('installation_id', '=', $installationId)->where('parent_id')->findFirst();
            if (!$rootSpace) {
                throw new \Exception('Installation does not have a root space');
            }

            $parentId = $rootSpace->space_id;
        }

        $spaceRepository->where('installation_id', '=', $installationId);

        if ($parentId !== false) {
            $spaceRepository->where('parent_id', '=', $parentId);
        } else {
            $spaceRepository->where('parent_id');
        }

        $spaces = $spaceRepository->findAll();

        return $this->response->withCollection($spaces, new SpaceTransformer());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->response->errorUnwillingToProcess();
    }

    /**
     * Display the specified resource.
     *
     * @param Space $space
     * @return mixed
     */
    public function show(Space $space)
    {
        // Return a single task
        return $this->response->withItem($space, new  SpaceTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Space $space
     * @return mixed
     */
    public function update(Request $request, Space $space)
    {
        return $this->response->errorUnwillingToProcess();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Space $space
     * @return mixed
     */
    public function destroy(Space $space)
    {
        return $this->response->errorUnwillingToProcess();
    }
}
