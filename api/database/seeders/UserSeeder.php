<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'Super Admin']);
        $user = User::create([
            'name' => 'Super Admin', 
            'email' => 'admin@cinebaz.com',
            'password' => bcrypt('Cinebaz007')
        ])->assignRole('Super Admin');


        $role = Role::create(['name' => 'Admin']);
        $user = User::create([
            'name' => 'Admin', 
            'email' => 'admin@news.com',
            'password' => bcrypt('admin123')
        ]);
        

        $permissions = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
    }
}
