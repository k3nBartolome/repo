<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('Personal Token')->plainTextToken;
            $role = $user->roles->first()->name;
            $permissions = $user->getAllPermissions()->pluck('name');
            $user_id = Auth::user()->id;

            return response()->json([
                'status' => 'success',
                'user' => $user,
                'token' => $token,
                'role' => $role,
                'permissions' => $permissions,
                'user_id' => $user_id,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid Credentials',
        ], 401);
    }

    public function logout($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->token()->revoke();

            return response()->json('Logged out', 200);
        } catch (ModelNotFoundException $e) {
            return response()->json('No user is currently authenticated', 401);
        } catch (\Exception $e) {
            // Log or handle other exceptions
            return response()->json('Internal Server Error', 500);
        }
    }
}
