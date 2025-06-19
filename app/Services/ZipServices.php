<?php
namespace App\Services;

use ZipArchive;
use App\Models\Template;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Enums\InformationSystemRequestPart;

class ZipServices
{
    protected const MIME_TYPE_GUESSER = [
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/msword' => 'doc',
        'application/pdf' => 'pdf'
    ];

    protected const ZIP_FILE_NAME = 'sop-dan-template-dokumen-pusdatin.zip';
    protected const CACHE_KEY = 'combined_zip_checksum';

    // Properties untuk menyimpan hasil getSopDocument dan getTemplates
    protected ?string $sopFilePath = null;
    protected $informationSystemTemplates = null;

    /**
     * Create a ZIP file from the given files.
     */
    public function createZip(array $files, string $zipFilePath)
    {
        // Check if temp directory exists, create if not
        $tempDirPath = 'temp';

        // Gunakan Storage facade untuk memeriksa dan membuat direktori
        if (!Storage::disk('local')->exists($tempDirPath)) {
            Storage::disk('local')->makeDirectory($tempDirPath);
        }

        // Initialize ZipArchive
        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \Exception('Gagal membuat file ZIP.');
        }

        try {
            foreach ($files as $file) {
                if (!$zip->addFile($file['path'], $file['name'])) {
                    throw new \Exception("Gagal menambahkan file {$file['name']} ke ZIP.");
                }
            }

            $zip->close();
        } catch (\Exception $e) {
            if (file_exists($zipFilePath)) {
                unlink($zipFilePath);
            }
            throw $e;
        }

        return $zipFilePath;
    }

    /**
     * Get the path to the SOP document.
     */
    public function getSopDocument(): string
    {
        if ($this->sopFilePath === null) {
            $sopFilePath = 'templates/SOP Pembangunan dan Pengembangan Aplikasi Pusdatin.pdf';

            if (!Storage::disk('local')->exists($sopFilePath)) {
                throw new \Exception('File SOP tidak ditemukan.');
            }

            $this->sopFilePath = $sopFilePath;
        }

        return $this->sopFilePath;
    }

    /**
     * Get active templates.
     */
    public function getInformationSystemTemplates()
    {
        if ($this->informationSystemTemplates === null) {
            $templates = Template::getActiveInformationSystemFilePath();

            if ($templates->isEmpty()) {
                throw new \Exception('Tidak ada template aktif yang tersedia.');
            }

            // Map the part_number to enum and create new file name
            $mappedTemplates = $templates->map(function ($template) {
                $partNumber = (int) $template->part_number;

                // Ensure part_number is valid for enum
                if (!InformationSystemRequestPart::tryFrom($partNumber)) {
                    throw new \Exception("Invalid part_number: {$partNumber}");
                }

                $label = InformationSystemRequestPart::from($partNumber)->label();

                $extension = self::MIME_TYPE_GUESSER[$template->mime_type] ?? null;

                return [
                    'file_path' => $template->file_path,
                    'new_name' => "{$partNumber}_{$label}.{$extension}",
                ];
            });

            $this->informationSystemTemplates = $mappedTemplates;
        }

        return $this->informationSystemTemplates;
    }

    /**
     * Generate a ZIP file containing SOP and active templates.
     */
    public function generateZip()
    {
        $sopFilePath = $this->getSopDocument();
        $templates = $this->getInformationSystemTemplates();

        // Prepare list of files to add to ZIP
        $filesToAdd = [
            ['path' => Storage::disk('local')->path($sopFilePath), 'name' => basename($sopFilePath)],
        ];

        foreach ($templates as $template) {
            $filesToAdd[] = [
                'path' => Storage::disk('local')->path($template['file_path']),
                'name' => "templates/{$template['new_name']}",
            ];
        }

        // Set ZIP file name
        $zipFileName = self::ZIP_FILE_NAME;
        $zipFilePath = Storage::disk('local')->path($zipFileName);

        // Create new ZIP file
        $this->createZip($filesToAdd, $zipFilePath);

        return $zipFilePath;
    }

    /**
     * Download the combined ZIP file of SOP and templates.
     */
    public function downloadSopAndTemplates()
    {
        $zipFileName = self::ZIP_FILE_NAME;
        $zipFilePath = Storage::disk('local')->path($zipFileName);

        // Calculate checksum to detect changes
        $checksum = $this->checkSum();

        // Check if ZIP file exists and is valid
        if (Storage::disk('local')->exists($zipFileName)) {
            $cachedChecksum = Cache::get(self::CACHE_KEY);
            if ($cachedChecksum === $checksum) {
                // Send existing ZIP file
                return response()->download($zipFilePath);
            }
        }

        // If no valid ZIP file exists, create a new one
        $newZipPath = $this->generateZip();

        // Save new checksum to cache
        Cache::put(self::CACHE_KEY, $checksum, now()->addHours(24));

        // Send new ZIP file
        return response()->download($newZipPath);
    }

    /**
     * Generates a checksum for the current SOP and information system templates.
     * The checksum is used to detect changes in files.
     *
     * @return string The MD5 checksum of the combined data.
     */
    public function checkSum()
    {
        $checksumData = [
            'sop_file' => $this->getSopDocument(),
            'templates' => $this->getInformationSystemTemplates()->map(fn($template) => [
                'file_path' => $template['file_path'],
            ])->toArray(),
        ];

        return md5(json_encode($checksumData));
    }
}