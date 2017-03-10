<?php

namespace App\Http\Requests;

use App\Http\Middleware\Authorise\Installation;
use Illuminate\Foundation\Http\FormRequest;

class SpaceList extends FormRequest
{
    use Installation;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->authoriseInstallation($this->request->get('installationId'));

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
            'installationId' => 'bail|required|numeric|min:1'
        ];
    }
}
