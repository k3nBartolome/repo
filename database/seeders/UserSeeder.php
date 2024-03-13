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
        $user=User::create(
            [
            'name'=>'Kryss Renato Engel Bartolome',
            'email'=>'user@user.com',
            'password'=>bcrypt('password')
            ] );

             $user1=User::create([
            'name'=>'pmercado',
            'email'=>'pmercado@vxi.com.ph',
            'password'=>bcrypt('password')
            ]);
            $user2=User::create([
                'name'=>'ldytioco',
                'email'=>'ldytioco@vxi.com.ph',
                'password'=>bcrypt('password')
                ] );
                $user3=User::create( [
                    'name'=>'xbarrantes',
                    'email'=>'xbarrantes@vxi.com.ph',
                    'password'=>bcrypt('password')
                    ] );
                    $user4=User::create(    [
                        'name'=>'agomez',
                        'email'=>'agomez@vxi.com.ph',
                        'password'=>bcrypt('password')
                        ] );
                        $user5=User::create(  [
                            'name'=>'jfabiano',
                            'email'=>'jfabiano@vxi.com.ph',
                            'password'=>bcrypt('password')
                            ]
                        );
                            $user6=User::create(   [
                                'name'=>'apascua',
                                'email'=>'apascua@vxi.com.ph',
                                'password'=>bcrypt('password')
                                ]
                            );
                                $user7=User::create(         [
                                    'name'=>'kolis',
                                    'email'=>'kolis@vxi.com.ph',
                                    'password'=>bcrypt('password')
                                    ]
    );
        $user->assignRole($user_role);
        $user1->assignRole($user_role);
        $user2->assignRole($user_role);
        $user3->assignRole($user_role);
        $user4->assignRole($user_role);
        $user5->assignRole($user_role);
        $user6->assignRole($user_role);
        $user7->assignRole($user_role);
        $user->givePermissionTo([
            $user_list,
        ]);
        $user->givePermissionTo([
            $user_list,
        ]);
        $user1->givePermissionTo([
            $user_list,
        ]);
        $user2->givePermissionTo([
            $user_list,
        ]);
        $user3->givePermissionTo([
            $user_list,
        ]);
        $user4->givePermissionTo([
            $user_list,
        ]);
        $user5->givePermissionTo([
            $user_list,
        ]);
        $user6->givePermissionTo([
            $user_list,
        ]);
        $user7->givePermissionTo([
            $user_list,
        ]);
    }
}
