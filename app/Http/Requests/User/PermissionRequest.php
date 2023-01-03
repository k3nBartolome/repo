<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'name'=>'required',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        //send error
        Helper::sendError('Validation error',$validator->errors());
    }
}
