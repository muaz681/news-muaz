<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'post-list',
            'post-create',
            'post-edit',
            'post-delete',
            'setting',
            'xml-upload',
            'image',
            'tag-list',
            'tag-create',
            'tag-edit',
            'tag-delete',
            'category-list',
            'category-create',
            'category-edit',
            'category-delete',
            'widget-list',
            'layout-list',
            'menu-list',
            'menu-create',
            'menu-edit',
            'menu-delete',
        ];

        foreach ($permissions as $permission) {
            try {
                Permission::create(['name' => $permission]);
            } catch (Exception $e) {
                echo $e->getMessage() . "\n";
            }
        }
    }
}
