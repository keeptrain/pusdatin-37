<?php

namespace App\Livewire\Forms;

use App\Enums\Division;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use App\Models\InformationSystemRequest;
use Illuminate\Support\Facades\DB;
use App\Services\FileUploadServices;
use App\Enums\InformationSystemRequestPart;

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
        $messages = [
            'title.required' => 'Judul harus ada',
            'reference_number.required' => 'Nomor surat harus ada',
        ];

        foreach (InformationSystemRequestPart::cases() as $part) {
            $messages["files.{$part->value}.required"] = "{$part->label()} harus ada";
            $messages["files.{$part->value}.mimes"] = "{$part->label()} harus berbentuk .pdf";
            $messages["files.{$part->value}.max"] = "{$part->label()} tidak boleh lebih dari 1MB";
        }

        return $messages;
    }

    public function save(FileUploadServices $fileUploadServices)
    {
        $this->validate();

        $systemRequestId = null;

        DB::transaction(function () use ($fileUploadServices, &$systemRequestId) {
            $validFiles = array_filter($this->files);

            $systemRequest = $this->create();
            $uploads = $fileUploadServices->storeMultiplesFiles($validFiles);
            $this->insertDocumentUploads($uploads, $systemRequest);
            $systemRequest->logStatus(null);

            // Send notification to head verifier
            $systemRequest->sendNewServiceRequestNotification('head_verifier');

            $systemRequestId = $systemRequest->id;
        });

        $this->redirectRoute('detail.request', ['type' => 'information-system', 'id' => $systemRequestId], true);
    }

    public function create(): InformationSystemRequest
    {
        return InformationSystemRequest::create([
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
