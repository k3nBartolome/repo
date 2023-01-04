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
    public function Login(Request $request)
    {

        $credentials = $request->validate([
            'email'=>'required|email|exists:users,email',
            'password'=>'required|min:8'
        ]);

        if($this->guard()->attempt($credentials)) {
            if ($this->guard()->user()->hasRole('admin')) {
                $token = $this->guard()->user()->createToken('auth-token')->plainTextToken;
                return response()->json([
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ], 200);
            }elseif($this->guard()->user()->hasRole('manager')) {
            $token = $this->guard()->user()->createToken('auth-token')->plainTextToken;
                return response()->json([
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ], 200);
            }else {
                $token = $this->guard()->user()->createToken('auth-token')->plainTextToken;
                return response()->json([
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ], 200);
            }
        }else{
            return response()->json([
                'message' => 'Credentials not Valid'
            ]);
        }
    }


    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $this->guard()->logout();
        return response()->json([
            'status_code' => '200',
            'message' => 'logged out successfully'
        ]);
    }

    public function guard($guard = 'web')
    {
        return Auth::guard($guard);
    }
}