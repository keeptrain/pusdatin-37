<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InformationSystemRequest;

class InformationSystemRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 1000; $i++) {
            InformationSystemRequest::create(
                [
                    'user_id' => random_int(7, 11),
                    'title' => fake()->sentence(),
                    'reference_number' => fake()->numberBetween(0, 1000),
                    'active_checking' => 2,
                    'current_division' => null,
                    'active_revision' => false,
                    'need_review' => false,
                ]
            );
        }
    }
}
