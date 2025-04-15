<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert(
        [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'password' => bcrypt('login'),
            ],
            [
                'name' => 'Mail Manager',
                'email' => 'mailmanager@gmail.com',
                'password' => bcrypt('login'),
            ],
        ]);

        // Create 5 users with the same password
        for ($i = 1; $i <= 5; $i++) {
            DB::table('users')->insert([
                'name' => 'User' . $i,
                'email' => 'user' . $i . '@gmail.com',
                'password' => bcrypt('login'),
            ]);
        }

        DB::table('roles')->insert([
            ['name' => 'Administrator'],
            ['name' => 'Verifikator'],
            ['name' => 'User'],
        ]);

        DB::table('role_user')->insert([
            [
                'user_id' => 1,
                'role_id' => 1,
            ],
            [
                'user_id' => 2,
                'role_id' => 2,
            ],
            [
                'user_id' => 3,
                'role_id' => 3,
            ],
            [
                'user_id' => 4,
                'role_id' => 3,
            ],
            [
                'user_id' => 5,
                'role_id' => 3,
            ],
            [
                'user_id' => 6,
                'role_id' => 3,
            ],
            [
                'user_id' => 7,
                'role_id' => 3,
            ],
        ]);
    }
}
