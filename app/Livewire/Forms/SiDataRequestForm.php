<?php

namespace App\Livewire\Forms;

use App\Enums\Division;
use Livewire\Component;
use App\Models\Template;
use Livewire\WithFileUploads;
use App\Models\Letters\Letter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\FileUploadServices;

class SiDataRequestForm extends Component
{
    use WithFileUploads;

    public $title = '';

    public $reference_number = '';

    public $files = [];

    public bool $fileReady = false;

    public function rules()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'reference_number' => 'required|string',
            'files.0' => 'required|file|mimes:pdf|max:1048',
            'files.1' => 'required|file|mimes:pdf|max:1048',
            'files.2' => 'required|file|mimes:pdf|max:1048',
            'files.3' => 'required|file|mimes:pdf|max:1048',
        ];

        if (!empty($this->files[4])) {
            $rules['files.4'] = 'file|mimes:pdf|max:1048';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'title.required' => 'Judul harus ada',
            'reference_number.required' => 'Nomor surat harus ada',
            'files.0.required' => 'Dokumen Identifikasi kebutuhan Pembangunan dan Pengembangan Aplikasi SPBE harus ada',
            'files.1.required' => 'SOP Aplikasi SPBE harus ada',
            'files.2.required' => 'Pakta Integritas Pemanfaatan Aplikasi harus ada',
            'files.3.required' => 'Form RFC Pusdatinkes harus ada',
            // 'files.4.required' => 'NDA Pusdatin Dinkes harus ada',
        ];
    }

    public function save(FileUploadServices $fileUploadServices)
    {
        $this->validate();

        DB::transaction(function () use($fileUploadServices) {
            $validFiles = array_filter($this->files);

            $letter = $this->createLetter();
            $uploads = $fileUploadServices->storeMultiplesFiles($validFiles);
            $this->insertDocumentUploads($uploads, $letter);
            // $this->createLetterMappings($letter->id, $uploadIds);
            $letter->logStatus(null);

            // Notifikasi kirim ke kapusdatin
            $letter->sendNewServiceRequestNotification('head_verifier');

            return redirect()->to('history')
                ->with('status', [
                    'variant' => 'success',
                    'message' => 'Create direct Letter successfully!'
                ]);
        });
    }

    public function createLetter()
    {
        return Letter::create([
            'user_id' => auth()->user()->id,
            'title' => $this->title,
            'reference_number' => $this->reference_number,
            'active_checking' => Division::HEAD_ID->value,
        ]);
    }

    protected function insertDocumentUploads(array $uploads, $letter)
    {
        $documentVersionId = collect();

        foreach ($uploads as $upload) {
            $documentUpload = $letter->documentUploads()->create([
                'part_number' => $upload['part_number']
            ]);

            $version = $documentUpload->versions()->create([
                'document_upload_id' => $documentUpload->id,
                'file_path' => $upload['file_path'],
                'is_resolved' => true,
            ]);

            $documentUpload->update([
                'document_upload_version_id' => $version->id,
            ]);

            $documentVersionId->push($documentUpload->id);
        }

        return $documentVersionId;
    }

    // protected function createLetterMappings(int $letterId, Collection $uploadIds): void
    // {
    //     $mappings = $uploadIds->map(function ($uploadId) use ($letterId) {
    //         return [
    //             'letter_id' => $letterId,
    //             'letterable_type' => DocumentUpload::class,
    //             'letterable_id' => $uploadId,
    //         ];
    //     })->toArray();

    //     LettersMapping::insert($mappings);
    // }

    public function downloadTemplate($typeNumber)
    {
        $template = Template::where('part_number', $typeNumber)->where('is_active', '1')->first();

        if ($template) {
            $filePath = $template->file_path;

            $fileDownload = Storage::disk('public')->path($filePath);

            return response()->download($fileDownload);
        }

        abort(404, 'Template not found.');
    }
}
