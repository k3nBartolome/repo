<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Helpers\Helper;
use App\Http\Resources\UserResource;
use App\Models\User;
use Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request){
        
        //user login
        if(!Auth::attempt($request->only('email','password'))){
            Helper::sendError('Email or Password is Invalid!!');
        }
        return new UserResource(auth()->user());
    }
}