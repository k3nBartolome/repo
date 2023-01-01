<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Helpers\Helper;
use Illuminate\Contracts\Validation\Validator;
use App\Models\User;

class LoginRequest extends FormRequest
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
            'email'=>'required|email|exists:users,email',
            'password'=>'required|min:8'
        ];
    }
    public function failedValidation(Validator $validator)
    {
        Helper::sendError('validation error',$validator->errors());
    }
}
