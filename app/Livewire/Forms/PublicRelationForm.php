<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use App\Models\Template;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use App\Models\PublicRelationRequest;
use Illuminate\Support\Facades\Storage;

class PublicRelationForm extends Component
{
    use WithFileUploads;

    public $monthPublication = '';

    public $spesificDate = '';

    public $theme = '';

    public $target = '';

    public $otherTarget = '';

    public $mediaType = [];

    public $uploadFile = [];

    public function rules()
    {
        $rules = [
            'monthPublication' => 'required|lt:12',
            'spesificDate' => 'required|date',
            'theme' => 'required|string',
            'target' => 'required|min:1',
            'mediaType' => 'required|min:1',
        ];

        if ($this->mediaType) {
            foreach ($this->mediaType as $index => $number) {
                $rules["uploadFile.$number"] = ['required'];
            }
        }

        if ($this->target === 'other') {
            $rules['otherTarget'] = ['required'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'otherTarget.required' => 'Harus mengisi sasaran other',
            'uploadFile.1' => 'Membutuhkan file untuk materi audio',
            'uploadFile.2' => 'Membutuhkan file untuk materi infografis',
            'uploadFile.3' => 'Membutuhkan file untuk materi poster',
            'uploadFile.4' => 'Membutuhkan file untuk materi video'
        ];
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $prData = $this->createPublicRelationForm();
            $prData->logStatus(null);
            $this->createDocumentUpload($prData);
            $prData->sendNewServiceRequestNotification('promkes_verifier');

            return $this->redirect("/history/public-relation/$prData->id", true);
        });
    }

    public function updatedOtherTarget($value)
    {
        if ($value && $this->target !== 'other') {
            $this->target = 'other';
        }
    }

    public function createPublicRelationForm()
    {
        $target = $this->target === 'other' ? $this->otherTarget : $this->target;

        return PublicRelationRequest::create([
            'user_id' => auth()->user()->id,
            'month_publication' => $this->monthPublication,
            'spesific_date' => $this->spesificDate,
            'theme' => $this->theme,
            'target' => $target,
            'active_checking' => 6
        ]);
    }

    public function updatedMediaType($value)
    {
        $allMediaTypes = ['1', '2', '3', '4'];
        foreach ($allMediaTypes as $type) {
            if (!in_array($type, $this->mediaType)) {
                $this->removeUploadedFile($type);
            }
        }
    }

    private function removeUploadedFile($type)
    {
        if (isset($this->uploadFile[$type])) {
            unset($this->uploadFile[$type]);
        }
    }

    public function collectUploadFile()
    {
        return collect($this->uploadFile)
            ->map(function ($file, $mediaType) {
                return [
                    'part_number' => $mediaType,
                    'file_path' => $file->store('documents', 'public'),
                ];
            })->toArray();
    }

    public function createDocumentUpload($prData)
    {
        $uploadedFilesData = $this->collectUploadFile();

        foreach ($uploadedFilesData as $fileData) {
            $documentUpload = $prData->documentUploads()->create([
                'part_number' => $fileData['part_number'],
            ]);

            $version = $documentUpload->versions()->create([
                'file_path' => $fileData['file_path'],
                'is_resolved' => true,
            ]);

            $documentUpload->update([
                'document_upload_version_id' => $version->id,
            ]);
        }
    }

    public function downloadTemplate()
    {
        $template = Template::select('part_number', 'is_active', 'file_path')->where('part_number', 6)->where('is_active', '1')->first();

        if ($template) {
            $filePath = $template->file_path;

            $fileDownload = Storage::disk('public')->path($filePath);

            return response()->download($fileDownload);
        }

        abort(404, 'Template not found.');
    }
}
