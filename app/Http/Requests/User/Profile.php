<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class Profile extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|bail|string|max:255|min:1',
            'handle' => 'required|bail|string|max:63|min:1',
        ];
    }
}
