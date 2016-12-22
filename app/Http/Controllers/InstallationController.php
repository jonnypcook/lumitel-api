<?php

namespace App\Http\Controllers;

use App\Installation;
use App\Transformer\InstallationTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class InstallationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Get all task
//        $installations = Installation::paginate(15);
        $installations = Installation::all();

        // Return a collection of $task with pagination
        //TODO: how do we transform data using transformers?
//        return $this->response->withPaginator($installations, new  InstallationTransformer());
        return $this->response->withCollection($installations, new InstallationTransformer());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->response->errorUnwillingToProcess();

        if ($request->isMethod('put')) {
            //Get the task
            $installation = Installation::find($request->installation_id);
            if (!$installation) {
                return $this->response->errorNotFound('Task Not Found');
            }
        } else {
            $installation = new Installation();
        }

        $installation->id = $request->input('installation_id');
        $installation->name = $request->input('name');
        $installation->commissioned = $request->input('commissioned');
        $installation->owner_id =  1; //$request->user()->id;
        $installation->address_id =  1; //$request->user()->id;

        if($installation->save()) {
            return $this->response->withItem($installation, new  TaskTransformer());
        } else {
            return $this->response->errorInternalError('Could not updated/created installation');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Get the task
        $installation = Installation::find($id);
        if (!$installation) {
            return $this->response->errorNotFound('Installation Not Found');
        }

        // Return a single task
        return $this->response->withItem($installation, new  InstallationTransformer());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
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
