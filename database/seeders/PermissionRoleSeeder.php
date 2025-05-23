<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'create',
            'read',
            'update',
            'delete',
        ];
        
        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Create roles
        $roles = [
            'administrator',
            'verifikator',
            'user',
        ];

        foreach ($roles as $role) {
            \Spatie\Permission\Models\Role::create([
                'name' => $role,
                'guard_name' => 'web'
            ]);
        }

        // Assign permissions to roles
        $role = \Spatie\Permission\Models\Role::findByName('administrator');
        $role->givePermissionTo($permissions);

        $role = \Spatie\Permission\Models\Role::findByName('verifikator');
        $role->givePermissionTo(['read', 'update']);

        $role = \Spatie\Permission\Models\Role::findByName('user');
        $role->givePermissionTo(['create','read','update','delete']);

    }
}
