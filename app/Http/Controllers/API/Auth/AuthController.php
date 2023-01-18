<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Http\Resources\UserResource;


class AuthController extends Controller
{
        public function login(Request $request)
        {
            $request->validate([
                'email'=>'required|email|exists:users,email',
                'password'=>'required|min:8'
            ]);

            $credentials = $request->only(['email', 'password']);

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('Personal Token')->plainTextToken;
                $role = $user->roles->first()->name;

                return response()->json([
                    'status' => 'success',
                    'user'=>$user,
                    'token' => $token,
                    'role'  => $role
                ], 200);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Credentials'
            ], 401);
        }
}
