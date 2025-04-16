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
                'name' => 'Administrator',
                'email' => 'administrator@gmail.com',
                'password' => bcrypt('login'),
            ],
            [
                'name' => 'Verifikator',
                'email' => 'verifikator@gmail.com',
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
    }
}
