<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class Password extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Hash::check($this->request->get('password'), \Auth::user()->password);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required|bail|string|min:4:max:16',
            'newPassword' => 'required|bail|string|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{4,16}$/',
        ];
    }
}
