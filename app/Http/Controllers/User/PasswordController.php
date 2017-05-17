<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\Password;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request, Password $password)
    {
        $user = $request->user();
        $user->password = Hash::make($request->get('newPassword'));

        $user->save();

        return $user;
    }
}
