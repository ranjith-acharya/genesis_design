<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'customer-list',
            'customer-create',
            'customer-edit',
            'customer-delete',
            'engineer-list',
            'engineer-create',
            'engineer-edit',
            'engineer-delete',
            'manager-list',
            'manager-create',
            'manager-edit',
            'manager-delete',
            'project-list',
            'project-create',
            'project-edit',
            'project-delete',
            'design-list',
            'design-create',
            'design-edit',
            'design-delete',
            'services-list',
            'services-create',
            'services-edit',
            'services-delete',
         ];

         foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
       }
    }
}
