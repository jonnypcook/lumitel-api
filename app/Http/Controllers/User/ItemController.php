<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request, Profile $profile)
    {
        $user = $request->user();
        $user->name = $request->get('name');
        $user->handle = $request->get('handle');

        $user->save();

        return $user;
    }
}
