<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class FileUploadServices
{
    public function generateFileName($file): string
    {
        $dateTime = Carbon::now()->format(format: 'YmdHis');

        $nameWithoutExtension = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $fileName = $nameWithoutExtension . '-' . $dateTime . '.pdf';

        return $fileName;
    }

    public function storeMultiplesFiles(array $uploadedFiles): array
    {
        return collect($uploadedFiles)
            ->sortKeys()
            ->values()
            ->map(function ($file, $index) {
                $fileName = $this->generateFileName($file);

                $filePath = Storage::disk('public')->putFileAs('documents', $file, $fileName);

                return [
                    'part_number' => $index,
                    'file_path' => $filePath,
                ];
            })->toArray();
    }

    public function storeMultiplesFilesPr(array $uploadedFiles)
    {
        return collect($uploadedFiles)
            ->sortKeys()
            ->map(function ($file, $mediaType) {
                $fileName = $this->generateFileName($file);

                $filePath = Storage::disk('public')->putFileAs('documents', $file, $fileName);

                return [
                    'part_number' => $mediaType,
                    'file_path' => $filePath,
                ];
            })->toArray();
    }
}
