<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use App\Models\Template;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use App\Services\FileUploadServices;
use App\Models\PublicRelationRequest;
use Illuminate\Support\Facades\Storage;

class PublicRelationForm extends Component
{
    use WithFileUploads;

    public $monthPublication = '';

    public $completedDate = '';

    public $spesificDate = '';

    public $theme = '';

    public $target = '';

    public $otherTarget = '';

    public $mediaType = [];

    public $uploadFile = [];

    public function rules()
    {
        $rules = [
            'monthPublication' => 'required|lt:13',
            'completedDate' => 'required|date|after_or_equal:' . now()->addDays(7)->toDateString(),
            'spesificDate' => 'required|date',
            'theme' => 'required|string',
            'target' => 'required|min:1',
            'mediaType' => 'required|min:1',
            'uploadFile.0' => 'required'
        ];

        if ($this->mediaType) {
            foreach ($this->mediaType as $index => $partNumber) {
                $rules["uploadFile.$partNumber"] = ['required'];
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
            'uploadFile.0' => 'Membutuhkan file nota dinas',
            'uploadFile.1' => 'Membutuhkan file untuk materi audio',
            'uploadFile.2' => 'Membutuhkan file untuk materi infografis',
            'uploadFile.3' => 'Membutuhkan file untuk materi poster',
            'uploadFile.4' => 'Membutuhkan file untuk materi video',
            'uploadFile.5' => 'Membutuhkan file untuk materi bumper',
            'uploadFile.6' => 'Membutuhkan file untuk materi backdrop Kegiata',
            'uploadFile.7' => 'Membutuhkan file untuk materi spanduk',
            'uploadFile.8' => 'Membutuhkan file untuk materi roll Banner',
            'uploadFile.9' => 'Membutuhkan file untuk materi sertifikat',
            'uploadFile.10' => 'Membutuhkan file untuk materi press Release',
            'uploadFile.11' => 'Membutuhkan file untuk materi artikel',
            'uploadFile.12' => 'Membutuhkan file untuk materi peliputan',
        ];
    }

    #[Computed(persist: true, cache:true)]
    public function getMonths()
    {
        $model = new PublicRelationRequest();

        $months = [];
        foreach (range(1, 12) as $month) {
            $months[$month] = $model->getMonthPublicationAttribute($month);
        }

        return $months;
    }

    public function save(FileUploadServices $fileUploadServices)
    {
        $this->validate();

        DB::transaction(function () use ($fileUploadServices) {
            $validFiles = array_filter($this->uploadFile);

            $prData = $this->createPublicRelationForm();
            $prData->logStatus(null);
            $uploads = $fileUploadServices->storeMultiplesFilesPr($validFiles, $this->mediaType);
            $this->insertDocumentUploads($uploads, $prData);
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
            'completed_date' => $this->completedDate,
            'spesific_date' => $this->spesificDate,
            'theme' => $this->theme,
            'target' => $target,
            'active_checking' => 6
        ]);
    }

    public function updatedMediaType($value)
    {
        $allMediaTypes = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11'];
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

    protected function insertDocumentUploads(array $uploads, $prRequest)
    {
        $documentVersionId = collect();

        foreach ($uploads as $upload) {
            $documentUpload = $prRequest->documentUploads()->create([
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
