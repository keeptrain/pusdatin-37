<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check apakahh directory templates tersedia
        if (!Storage::disk('public')->exists('templates')) {
            Storage::disk('public')->makeDirectory('templates');
        }

        // Definisi template dalam bentuk array
        $templatesToSeed = [
            [
                'name' => 'Template si & data 1',
                'part_number' => 1,
                'filename' => '1. Dokumen Identifikasi kebutuhan Pembangunan dan Pengembangan Aplikasi SPBE(I.A).docx',
                'is_active' => true,
            ],
            [
                'name' => 'Template si & data 2',
                'part_number' => 2,
                'filename' => '2. SOP Aplikasi SPBE (I.A2).docx',
                'is_active' => true,
            ],
            [
                'name' => 'Template si & data 3',
                'part_number' => 3,
                'filename' => 'Pakta Integritas Pemanfaatan Aplikasi.docx',
                'is_active' => true,
            ],
            [
                'name' => 'Template si & data 4',
                'part_number' => 4,
                'filename' => 'Form RFC_Pusdatinkes.docx',
                'is_active' => true,
            ],
            [
                'name' => 'Template si & data 5',
                'part_number' => 5,
                'filename' => 'NDA PUSDATIN DINKES.doc',
                'is_active' => true,
            ],
            [
                'name' => 'Template humas',
                'part_number' => 6,
                'filename' => 'Template Materi Edukasi.docx',
                'is_active' => true,
            ],
        ];

        $seededCount = 0;
        foreach ($templatesToSeed as $templateData) {
            $filePathInStorage = 'templates/' . $templateData['filename'];

            if (Storage::disk('public')->exists($filePathInStorage)) {
                Template::create(
                    [
                        'name' => $templateData['name'],
                        'part_number' => $templateData['part_number'],
                        'file_path' => $filePathInStorage,
                        'is_active' => $templateData['is_active']
                    ]

                );
                $seededCount++;
            } else {
                $this->command->warn("File tidak ditemukan di public/storage/: " . $filePathInStorage);
            }
        }

        $this->command->info($seededCount . ' template files seeded successfully!');
    }
}
