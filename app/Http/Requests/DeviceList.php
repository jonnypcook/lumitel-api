<?php

namespace App\Http\Requests;

use App\Http\Middleware\Authorise\Installation;
use App\Http\Middleware\Authorise\Space;
use Illuminate\Foundation\Http\FormRequest;

class DeviceList extends FormRequest
{
    use Space, Installation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->request->get('installationId')) {
            $this->authoriseInstallation($this->request->get('installationId'));
            return true;
        }

        if ($this->request->get('spaceId')) {
            $this->authoriseSpace($this->request->get('spaceId'));
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'installationId' => 'bail|required_without:spaceId|numeric|min:1',
            'spaceId' => 'bail|required_without:installationId|numeric|min:1',
            'metering' => 'bail|boolean',
        ];
    }
}
