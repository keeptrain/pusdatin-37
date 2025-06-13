<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Arr;
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
                'section' => 'Pusdatin',
                'contact' => '08123456789'
        ]);
        $admin->assignRole($adminRole);

        $headVerifierRole = Role::findByName('head_verifier');
        $verifikator = User::create([
                'name' => 'Kapusdatin',
                'email' => 'kapusdatin@gmail.com',
                'password' => bcrypt('login'),
                'section' => 'Pusdatin',
                'contact' => '08123456789'
        ]);
        $verifikator->assignRole($headVerifierRole);

        $verifikatorRole = Role::findByName('si_verifier');
        $verifikator = User::create([
                'name' => 'Kasatpel SI',
                'email' => 'kasatpel_si@gmail.com',
                'password' => bcrypt('login'),
                'section' => 'Pusdatin',
                'contact' => '08123456789'
        ]);
        $verifikator->assignRole($verifikatorRole);

        $verifikatorRole = Role::findByName('data_verifier');
        $verifikator = User::create([
                'name' => 'Kasatpel Data',
                'email' => 'kasatpel_data@gmail.com',
                'password' => bcrypt('login'),
                'section' => 'Pusdatin',
                'contact' => '08123456789'
        ]);
        $verifikator->assignRole($verifikatorRole);

        $verifikatorRole = Role::findByName('pr_verifier');
        $verifikator = User::create([
                'name' => 'Kasatpel Humas',
                'email' => 'kasatpel_humas@gmail.com',
                'password' => bcrypt('login'),
                'section' => 'Pusdatin',
                'contact' => '08123456789'
                
        ]);
        $verifikator->assignRole($verifikatorRole);

        $verifikatorRole = Role::findByName('promkes_verifier');
        $verifikator = User::create([
                'name' => 'Promosi Kesehatan',
                'email' => 'promkes@gmail.com',
                'password' => bcrypt('login'),
                'section' => 'Promosi Kesehatan',
                'contact' => '08123456789'
        ]);
        $verifikator->assignRole($verifikatorRole);

        $userRole = Role::findByName('user');  
        $sections = ['kepegawaian', 'kesehatan', 'tenaga kesehatan', 'umum'];      
        for ($i = 1; $i <= 5; $i++) {
            $regularUsers = User::create([
                'name' => 'User' . $i,
                'email' => 'user' . $i . '@gmail.com',
                'password' => bcrypt('login'),
                'section' => Arr::random($sections),
                'contact' => '08123456789'

            ]);
            $regularUsers->assignRole($userRole);
        }
    }
}
