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

        $generalPermissions = [
            'view notifications',
            'view discussions',
            'view ratings',
            'view analytics',
        ];

        $administratorPermissions = [
            'create user',
            'update user',
            'delete user',
            'view users',
        ];

        $informationSystemPermissions = [
            'view si request',
            'can process si',
            'verification request si step1',
            'review revision si',
        ];

        $dataSystemPermissions = [
            'view data request',
            'can process data',
            'verification request data step1',
            'review revision data',
        ];

        $publicRelationPermissions = [
            'view pr request',
            'process pr pusdatin',
            'completing pr request',
        ];

        $promkesPermissions = [
            'queue pr promkes',
            'curation',
        ];

        $headPermissions = [
            'can disposition',
            'verification request si-data step2',
            'review request si-data step2',
            'disposition pr pusdatin',
            'queue pr pusdatin',
        ];

        $siDataPermisions = [
            'completed request'
        ];

        $userPermissions = [
            'create request',
            'view requests',
            'revision si-data request',
        ];

        $permissions = array_unique([
            ...$generalPermissions,
            ...$administratorPermissions,
            ...$headPermissions,
            ...$informationSystemPermissions,
            ...$dataSystemPermissions,
            ...$siDataPermisions,
            ...$publicRelationPermissions,
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
        $role->givePermissionTo($administratorPermissions);

        $role = \Spatie\Permission\Models\Role::findByName('head_verifier');
        $viewPermissions = ['view pr request', 'view si request', 'view data request'];
        $role->givePermissionTo([$generalPermissions, $headPermissions, $viewPermissions]);

        $role = \Spatie\Permission\Models\Role::findByName('si_verifier');
        $role->givePermissionTo([$generalPermissions, $informationSystemPermissions, $siDataPermisions]);

        $role = \Spatie\Permission\Models\Role::findByName('data_verifier');
        $role->givePermissionTo([$generalPermissions, $dataSystemPermissions, $siDataPermisions]);

        $role = \Spatie\Permission\Models\Role::findByName('pr_verifier');
        $role->givePermissionTo([$generalPermissions, $publicRelationPermissions]);

        $role = \Spatie\Permission\Models\Role::findByName('promkes_verifier');
        $role->givePermissionTo([$promkesPermissions, 'view pr request'], 'view discussions', 'view notifications');

        $role = \Spatie\Permission\Models\Role::findByName('user');
        $role->givePermissionTo($userPermissions);
    }
}
