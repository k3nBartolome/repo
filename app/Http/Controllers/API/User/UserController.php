<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\User\UserRequest;
use App\Helpers\Helper;
use App\Http\Resources\UserResource;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user =User::paginate(10);
        return UserResource::collection($user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        //register user
        $user=User::create([    
         
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password)
        
        ]);
            //assign role and permission
            $user_role =($request->input('role'));
            $role_permission =Role::findByName($request->input('role'))->permissions;
            if($user_role){
                $user->assignRole($user_role);
                $user->givePermissionTo($role_permission);
            }
            return new UserResource($user);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user=User::FindOrFail($id);
        return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
       $user=User::FindOrFail($id);
       $user->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password)
        ]);
        // $user_role =($request->input('role'));
        $user_role =($request->input('role'));
        $role_permission =Role::findByName($request->input('role'))->permissions;
        if($user_role){
            $user->syncRoles($user_role);
            $user->syncPermissions($role_permission);
        }
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        {
            $user=User::FindOrFail($id);
            if($user->delete()){
            return new UserResource($user);
            }
        }
    }
}
