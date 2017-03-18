<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeviceData extends FormRequest
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
            'dateFrom' => 'required|bail|date',
            'dateTo' => 'required|bail|date',
            'resultsPerPage' => 'bail|numeric|min:1|max:300',
            'pageNumber' => 'bail|numeric|min:1',
        ];
    }
}
