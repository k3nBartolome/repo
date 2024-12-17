<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(100);

        return UserResource::collection($users);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'site_id' => 'sometimes|integer|exists:sites,id',
            'role' => 'sometimes|string|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'site_id' => $validatedData['site_id'],
            'password' => bcrypt($validatedData['password']),
        ]);

        $user_role = $validatedData['role'];
        if ($user_role) {
            $role = Role::where('name', $user_role)->first();
            if (!$role) {
                // Role not found, halt the process
                return response()->json(['error' => 'Role not found'], 400);
            }

            $user->assignRole($role);

            // Give the user the permissions associated with the role
            $permissions = $role->permissions;
            $user->syncPermissions($permissions);
        }

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::FindOrFail($id);

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,'.$id,
            'password' => 'sometimes|string|min:6',
            'role' => 'sometimes|string|exists:roles,name',
        ]);

        $user = User::FindOrFail($id);
        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);
        $user_role = ($validatedData['role']);
        $role_permission = Role::findByName($validatedData['role'])->permissions;
        if ($user_role) {
            $user->syncRoles($user_role);
            $user->syncPermissions($role_permission);
        }

        return new UserResource($user);
    }

    public function update_profile(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name' => 'nullable|string',
            'email' => 'sometimes|email|unique:users,email,'.$user->id,
            'password' => 'nullable|min:8',
        ];

        if ($request->filled('password')) {
            $rules['password_confirmation'] = 'required|same:password';
        }

        $this->validate($request, $rules);

        if ($request->filled('name')) {
            $user->name = $request->input('name');
        }
        if ($request->filled('email')) {
            $user->email = $request->input('email');
        }
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        return response()->json(['user' => $user, 'message' => 'Profile updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::FindOrFail($id);
        if ($user->delete()) {
            return new UserResource($user);
        }
    }
}
