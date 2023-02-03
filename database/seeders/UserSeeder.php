<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_list = Permission::create(['name'=>'users.list']);
        $user_show = Permission::create(['name'=>'users.show']);
        $user_create = Permission::create(['name'=>'users.create']);
        $user_update = Permission::create(['name'=>'users.update']);
        $user_delete = Permission::create(['name'=>'users.delete']);

        $admin_role = Role::create(['name'=>'admin']);
        $user_role = Role::create(['name'=>'user']);


        $admin_role->givePermissionTo([
            $user_create,
            $user_show,
            $user_update,
            $user_delete,
            $user_list
        ]);
        $user_role -> givePermissionTo([
            $user_list,
        ]);
        $admin=User::create([
            'name'=>'Admin',
            'email'=>'admin@admin.com',
            'password'=>bcrypt('password')
        ]);
        $admin->assignRole($admin_role);
        $admin->givePermissionTo([
            $user_create,
            $user_show,
            $user_update,
            $user_delete,
            $user_list
        ]);
        $user=User::create([
            'name'=>'User',
            'email'=>'user@user.com',
            'password'=>bcrypt('password')
        ]);
        $user->assignRole($user_role);
        $user->givePermissionTo([
            $user_list,
        ]);
    }
}
