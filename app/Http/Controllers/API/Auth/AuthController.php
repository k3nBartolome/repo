<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
    
        // Convert email to lowercase for case-insensitive comparison
        $inputEmail = strtolower($request->email);
    
        // Attempt to find the user by email (case-insensitive)
        $user = User::whereRaw('LOWER(email) = ?', [$inputEmail])->first();
    
        // Check if user exists and password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Credentials',
            ], 401);
        }
    
        // Generate the Sanctum token
        $token = $user->createToken('Personal Token')->plainTextToken;
    
        // Get user's role and permissions
        $role = $user->roles->first()->name ?? null; // Role may not exist
        $permissions = $user->getAllPermissions()->pluck('name');
        $user_id = $user->id;
    
        // Get the site_id and assigned site names using table aliases
        $user_sites = $user->sites()->select('user_site.site_id')->pluck('user_site.site_id'); // Specify the table alias here
        $assignedSites = $user->sites()->select('sites.name')->pluck('sites.name'); // Specify the table alias here
    
        // Return response with token, user details, and assigned sites
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'token' => $token,
            'role' => $role,
            'permissions' => $permissions,
            'user_id' => $user_id,
            'site_id' => $user_sites, // Include site IDs in the response
            'assigned_sites' => $assignedSites, // Include assigned site names in the response
        ], 200);
    }
    
    


    public function logout($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->tokens()->where('id', $id)->delete();
    
            return response()->json('Logged out', 200);
        } catch (ModelNotFoundException $e) {
            return response()->json('No user is currently authenticated', 401);
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error during logout:', $e->getMessage());
            return response()->json('Internal Server Error', 500);
        }
}
}
