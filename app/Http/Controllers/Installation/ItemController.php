<?php

namespace App\Http\Controllers\Installation;

use App\Models\Installation;
use App\Repositories\InstallationRepository;
use App\Transformer\InstallationTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    /**
     * @param Request $request
     * @param InstallationRepository $installationRepository
     * @return mixed
     */
    public function index(Request $request, InstallationRepository $installationRepository)
    {
        $userId = $request->user()->id;

        //Get all installations for user
        $installations = $installationRepository->findWhereHas([
            'users',
            function($query) use($userId) {
                $query->where('user_id', $userId);
            }
        ]);

        return $this->response->withCollection($installations, new InstallationTransformer());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->response->errorUnwillingToProcess();
//        if($installation->save()) {
//            return $this->response->withItem($installation, new  TaskTransformer());
//        } else {
//            return $this->response->errorInternalError('Could not updated/created installation');
//        }

    }

    /**
     * Display the specified resource.
     *
     * @param Installation $installation
     * @return mixed
     */
    public function show(Installation $installation)
    {
        // Return a single task
        return $this->response->withItem($installation, new  InstallationTransformer());
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Installation $installation
     * @return mixed
     */
    public function update(Request $request, Installation $installation)
    {
        return $this->response->errorUnwillingToProcess();
//        if($installation->save()) {
//            return $this->response->withItem($installation, new  TaskTransformer());
//        } else {
//            return $this->response->errorInternalError('Could not updated/created installation');
//        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Installation $installation
     * @return mixed
     */
    public function destroy(Installation $installation)
    {
        return $this->response->errorUnwillingToProcess();

        //Get the installation
        $installation = Installation::find($id);
        if (!$installation) {
            return $this->response->errorNotFound('Installation Not Found');
        }

        if($installation->delete()) {
            return $this->response->withItem($installation, new  TaskTransformer());
        } else {
            return $this->response->errorInternalError('Could not delete installation');
        }
    }
}

