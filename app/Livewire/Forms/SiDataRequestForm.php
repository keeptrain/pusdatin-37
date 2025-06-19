<?php

namespace App\Livewire\Forms;

use App\Enums\Division;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use App\Models\Letters\Letter;
use Illuminate\Support\Facades\DB;
use App\Services\FileUploadServices;

#[Title('Form Sistem Informasi & Data')]
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
            'files.4' => 'required|file|mimes:pdf|max:1048',
        ];

        if (!empty($this->files[5])) {
            $rules['files.5'] = 'file|mimes:pdf|max:1048';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'title.required' => 'Judul harus ada',
            'reference_number.required' => 'Nomor surat harus ada',
            'files.0.required' => 'Permohonan (nota dinas) harus ada',
            'files.1.required' => 'Dokumen Identifikasi kebutuhan Pembangunan dan Pengembangan Aplikasi SPBE harus ada',
            'files.2.required' => 'SOP Aplikasi SPBE harus ada',
            'files.3.required' => 'Pakta Integritas Pemanfaatan Aplikasi harus ada',
            'files.4.required' => 'Form RFC Pusdatinkes harus ada',
            'files.0.mimes' => 'Permohonan (nota dinas) harus ada',
            'files.1.mimes' => 'Dokumen Identifikasi kebutuhan Pembangunan dan Pengembangan Aplikasi SPBE harus berbentuk .pdf',
            'files.2.mimes' => 'SOP Aplikasi SPBE harus berbentuk .pdf',
            'files.3.mimes' => 'Pakta Integritas Pemanfaatan Aplikasi harus berbentuk .pdf',
            'files.4.mimes' => 'Form RFC Pusdatinkes harus berbentuk .pdf',
            'files.5.mimes' => 'Surat perjanjian kerahasiaan harus berbentuk .pdf',
        ];
    }

    public function save(FileUploadServices $fileUploadServices)
    {
        $this->validate();

        DB::transaction(function () use ($fileUploadServices) {
            $validFiles = array_filter($this->files);

            $letter = $this->createLetter();
            $uploads = $fileUploadServices->storeMultiplesFiles($validFiles);
            $this->insertDocumentUploads($uploads, $letter);
            $letter->logStatus(null);

            // Notifikasi kirim ke kapusdatin
            $letter->sendNewServiceRequestNotification('head_verifier');

            return $this->redirect("/history/information-system/$letter->id", true);
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
}
