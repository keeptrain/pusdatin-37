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
            'section' => 'pusdatin',
            'contact' => '08123456789'
        ]);
        $admin->assignRole($adminRole);

        $headVerifierRole = Role::findByName('head_verifier');
        $createHeadVerifier = User::create([
            'name' => 'Kapusdatin',
            'email' => 'remajamesjid1945@gmail.com',
            'password' => bcrypt('login'),
            'section' => 'pusdatin',
            'contact' => '08123456789'
        ]);
        $createHeadVerifier->assignRole($headVerifierRole);

        $verifikatorSiRole = Role::findByName('si_verifier');
        $createSiVerifier = User::create([
            'name' => 'Kasatpel SI',
            'email' => 'cgilang02@gmail.com',
            'password' => bcrypt('login'),
            'section' => 'pusdatin',
            'contact' => '08123456789'
        ]);
        $createSiVerifier->assignRole($verifikatorSiRole);

        $verifikatorDataRole = Role::findByName('data_verifier');
        $createDataVerifier = User::create([
            'name' => 'Kasatpel Data',
            'email' => 'kasatpel_data@gmail.com',
            'password' => bcrypt('login'),
            'section' => 'pusdatin',
            'contact' => '08123456789'
        ]);
        $createDataVerifier->assignRole($verifikatorDataRole);

        $verifikatorPrRole = Role::findByName('pr_verifier');
        $createPrVerifier = User::create([
            'name' => 'Kasatpel Humas',
            'email' => 'kasatpel_humas@gmail.com',
            'password' => bcrypt('login'),
            'section' => 'pusdatin',
            'contact' => '08123456789'

        ]);
        $createPrVerifier->assignRole($verifikatorPrRole);

        $verifikatorPromkesRole = Role::findByName('promkes_verifier');
        $createPromkesVerifier = User::create([
            'name' => 'Promosi Kesehatan',
            'email' => 'promkes@gmail.com',
            'password' => bcrypt('login'),
            'section' => 'promkes',
            'contact' => '08123456789'
        ]);
        $createPromkesVerifier->assignRole($verifikatorPromkesRole);

        $userRole = Role::findByName('user');
        $sections = [
            'pusdatin' => 'Pusdatin',
            'promkes' => 'Promosi Kesehatan',
            'kepegawaian' => 'Kepegawaian',
            'kesehatan' => 'Kesehatan',
            'tenaga_kesehatan' => 'Tenaga Kesehatan',
        ];
        for ($i = 1; $i <= 5; $i++) {
            $regularUsers = User::create([
                'name' => fake()->name(),
                'email' => 'user' . $i . '@gmail.com',
                'password' => bcrypt('login'),
                'section' => array_rand($sections),
                'contact' => fake()->phoneNumber()
            ]);
            $regularUsers->assignRole($userRole);
        }

        $regularUser = User::create([
            'name' => fake()->name(),
            'email' => '19211020@bsi.ac.id',
            'password' => bcrypt('login'),
            'section' => array_rand($sections),
            'contact' => fake()->phoneNumber()
        ]);
        $regularUser->assignRole($userRole);
    }
}
