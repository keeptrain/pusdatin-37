<?php

namespace Database\Seeders;

use App\Models\PublicRelationRequest;
use Illuminate\Database\Seeder;
use Faker\Faker;

class PublicRelationRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $target = ['masyarakat_umum', 'tenaga_kesehatan', 'anak_sekolah', 'semua_orang'];

        for ($i = 0; $i < 1000; $i++) {
            PublicRelationRequest::create([
                'user_id' => random_int(7, 11),
                'month_publication' => random_int(1, 12),
                'completed_date' => fake()->date(),
                'spesific_date' => fake()->date(),
                'theme' => fake()->sentence(),
                'target' => json_encode($target),
                'links' => null,
                'active_checking' => 6,
                'rating' => null
            ]);
        }
    }
}
