<?php

namespace App\Http\Requests\User;
use App\Http\Helpers\Helper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
            'name'=>'required',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        //send error
        Helper::sendError('Validation error',$validator->errors());
    }
}
