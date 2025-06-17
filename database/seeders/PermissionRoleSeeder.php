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
        $permissions = [];

        $headPermissions = [
            'can disposition',
            'verification request si-data step2',
            'review request si-data step2',
            'queue pr pusdatin',
            'disposition pr pusdatin',
        ];

        $siPermissions = [
            'view si request',
            'can process si',
            'verification request si step1',
            'review revision si',
        ];

        $dataPermissions = [
            'view data request',
            'can process data',
            'verification request data step1',
            'review revision data',
        ];

        $siDataPermisions = [
            'completed request'
        ];

        $prPermisions = [
            'view pr request',
            'process pr pusdatin',
            'completing pr request',
        ];

        $promkesPermissions = [
            'queue pr promkes',
            'curation',
        ];

        $userPermissions = [
            'create request',
            'view requests',
            'revision si-data request',
        ];

        $permissions = array_unique([
            ...$headPermissions,
            ...$siPermissions,
            ...$dataPermissions,
            ...$siDataPermisions,
            ...$prPermisions,
            ...$promkesPermissions,
            ...$userPermissions,
        ]);

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Create roles
        $roles = [
            'administrator',
            'head_verifier',
            'si_verifier',
            'data_verifier',
            'pr_verifier',
            'promkes_verifier',
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

        $role = \Spatie\Permission\Models\Role::findByName('head_verifier');
        $role->givePermissionTo($headPermissions, 'view pr request', 'view si request', 'view data request');

        $role = \Spatie\Permission\Models\Role::findByName('si_verifier');
        $role->givePermissionTo([$siPermissions, $siDataPermisions]);

        $role = \Spatie\Permission\Models\Role::findByName('data_verifier');
        $role->givePermissionTo([$dataPermissions, $siDataPermisions]);

        $role = \Spatie\Permission\Models\Role::findByName('pr_verifier');
        $role->givePermissionTo([$prPermisions]);

        $role = \Spatie\Permission\Models\Role::findByName('promkes_verifier');
        $role->givePermissionTo($promkesPermissions, 'view pr request',);

        $role = \Spatie\Permission\Models\Role::findByName('user');
        $role->givePermissionTo($userPermissions);
    }
}
