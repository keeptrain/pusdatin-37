<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use App\Models\PublicRelationRequest;
use App\Models\Letters\RequestStatusTrack;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewServiceRequestNotification;

class PublicRelationForm extends Component
{
    use WithFileUploads;

    public $month = '';

    public $spesificDate = '';

    public $theme = '';

    public $target = '';

    public $mediaType = [];

    public $uploadFile = [];

    public function rules()
    {
        $rules = [
            'month' => 'required',
            'theme' => 'required|string',
            'target' => 'required|min:1',
            'mediaType' => 'nullable|min:1',
        ];

        if ($this->mediaType) {
            foreach ($this->mediaType as $index => $number) {
                $rules["uploadFile.$number"] = ['required'];
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [];
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $prData = $this->createPublicRelationForm();
            $this->createRequestStatusTrack($prData);
            $this->createDocumentUpload($prData);
            $this->handleNotification($prData);
        });
    }

    public function createPublicRelationForm()
    {
        return PublicRelationRequest::create([
            'user_id' => auth()->user()->id,
            'month_publication' => now(),
            'spesific_date' => now(),
            'theme' => $this->theme,
            'target' => $this->target,
            'active_review' => true
        ]);
    }

    public function createRequestStatusTrack(PublicRelationRequest $prData)
    {
        RequestStatusTrack::create([
            'statusable_id' => $prData->id,
            'statusable_type' => PublicRelationRequest::class,
            'action' => $prData->status->trackingActivity(null),
            'created_by' => auth()->user()->name
        ]);
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
                'documentable_id' => $prData->id,
                'documentable_type' => get_class($prData),
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

    public function handleNotification($prData)
    {
        $user = User::role('promkes_verifier')->get();
        Notification::send($user, new NewServiceRequestNotification($prData));
    }
}
