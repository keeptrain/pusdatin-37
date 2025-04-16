<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::findByName('administrator');
        $admin = User::create([
            'name' => 'Administrator',
                'email' => 'administrator@gmail.com',
                'password' => bcrypt('login'),

        ]);
        $admin->assignRole($adminRole);

        $verifikatorRole = Role::findByName('verifikator');
        $verifikator = User::create([
                'name' => 'Verifikator',
                'email' => 'verifikator@gmail.com',
                'password' => bcrypt('login'),
        ]);
        $verifikator->assignRole($verifikatorRole);

        $userRole = Role::findByName('user');        
        for ($i = 1; $i <= 5; $i++) {
            $regularUsers = User::create([
                'name' => 'User' . $i,
                'email' => 'user' . $i . '@gmail.com',
                'password' => bcrypt('login'),
            ]);
            $regularUsers->assignRole($userRole);
        }
    }
}
