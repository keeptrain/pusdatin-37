<?php

namespace Database\Seeders;

use App\Models\Template;
use GuzzleHttp\Psr7\MimeType;
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
        if (!Storage::disk('local')->exists('templates')) {
            Storage::disk('local')->makeDirectory('templates');
        }

        // Definisi template dalam bentuk array
        $templatesToSeed = [
            [
                'name' => 'Template si & data 1',
                'part_number' => 1,
                'filename' => '1. Dokumen Identifikasi kebutuhan Pembangunan dan Pengembangan Aplikasi SPBE(I.A).docx',
                'mime_type' => MimeType::fromFilename('1. Dokumen Identifikasi kebutuhan Pembangunan dan Pengembangan Aplikasi SPBE(I.A).docx'),
                'is_active' => true,
            ],
            [
                'name' => 'Template si & data 2',
                'part_number' => 2,
                'filename' => '2. SOP Aplikasi SPBE (I.A2).docx',
                'mime_type' => MimeType::fromFilename('2. SOP Aplikasi SPBE (I.A2).docx'),
                'is_active' => true,
            ],
            [
                'name' => 'Template si & data 3',
                'part_number' => 3,
                'filename' => '3. Pakta Integritas Pemanfaatan Aplikasi.docx',
                'mime_type' => MimeType::fromFilename('3. Pakta Integritas Pemanfaatan Aplikasi.docx'),
                'is_active' => true,
            ],
            [
                'name' => 'Template si & data 4',
                'part_number' => 4,
                'filename' => '4. Form RFC_Pusdatinkes.docx',
                'mime_type' => MimeType::fromFilename('4. Form RFC_Pusdatinkes.docx'),
                'is_active' => true,
            ],
            [
                'name' => 'Template si & data 5',
                'part_number' => 5,
                'filename' => '5. NDA PUSDATIN DINKES.doc',
                'mime_type' => MimeType::fromFilename('5. NDA PUSDATIN DINKES.doc'),
                'is_active' => true,
            ],
            [
                'name' => 'Template humas',
                'part_number' => 6,
                'filename' => 'Template Materi Edukasi.docx',
                'mime_type' => MimeType::fromFilename('Template Materi Edukasi.docx'),
                'is_active' => true,
            ],
        ];

        $seededCount = 0;
        foreach ($templatesToSeed as $templateData) {
            $filePathInStorage = 'templates/' . $templateData['filename'];

            if (Storage::disk('local')->exists($filePathInStorage)) {
                Template::create(
                    [
                        'name' => $templateData['name'],
                        'part_number' => $templateData['part_number'],
                        'file_path' => $filePathInStorage,
                        'mime_type' => $templateData['mime_type'],
                        'is_active' => $templateData['is_active']
                    ]

                );
                $seededCount++;
            } else {
                $this->command->warn("File not found on local storage: $filePathInStorage");
            }
        }

        $this->command->info("$seededCount template files seeded successfully!");
    }
}
