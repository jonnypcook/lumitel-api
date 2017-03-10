<?php

namespace App\Http\Controllers\Space;

use App\Http\Requests\SpaceList;
use App\Models\Space;
use App\Repositories\SpaceRepository;
use App\Transformer\SpaceTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    /**
     * @param Request $request
     * @param SpaceRepository $spaceRepository
     * @return mixed
     */
    public function index(Request $request, SpaceList $spaceList, SpaceRepository $spaceRepository)
    {
        $installationId = $request->get('installationId', false);

        $spaces = $spaceRepository->where('installation_id', '=', $installationId)->findAll();

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
