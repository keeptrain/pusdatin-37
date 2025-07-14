<?php

namespace App\Livewire\Requests\User;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;
use App\Services\TrackingStepped;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use App\Models\PublicRelationRequest;
use App\Models\InformationSystemRequest;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Illuminate\Validation\Rule;
use App\Enums\PublicRelationRequestPart;

#[Title('Detail Permohonan')]
class Detail extends Component
{
    use WithFileUploads;

    #[Locked]
    public int $id;

    public $type;

    public $additionalFile;

    public array $rating = [
        'value' => null,
        'comment' => ''
    ];

    public $content;

    public $meetings;

    public function mount($type, int $id)
    {
        $this->id = $id;
        $this->type = $type;
        $this->loadContent();
        if (method_exists($this->content, 'meetings')) {
            $this->meetings = $this->content?->meetings()->get() ?? null;
        }
    }

    protected function loadContent()
    {
        $this->content = match ($this->type) {
            'information-system' => $this->systemRequests(),
            'public-relation' => $this->prRequests(),
            default => abort(404, 'Invalid content type.')
        };
    }

    public function systemRequests()
    {
        return InformationSystemRequest::with('documentUploads.activeVersion:id,file_path')->findOrFail($this->id);
    }

    public function prRequests()
    {
        return PublicRelationRequest::with('documentUploads.activeVersion:id,file_path')->findOrFail($this->id);
    }

    #[Computed]
    public function nearestMeeting()
    {
        return $this->content?->getNearestMeetingFromCollection($this->meetings);
    }

    #[Computed]
    public function statuses()
    {
        return match ($this->type) {
            'information-system' => TrackingStepped::SiDataRequest($this->content),
            'public-relation' => TrackingStepped::PublicRelationRequest($this->content),
            default => abort(404, 'Invalid content type.'),
        };
    }

    #[Computed]
    public function currentIndex()
    {
        $statuses = $this->statuses;
        return TrackingStepped::currentIndex($this->content, $statuses);
    }

    #[Computed]
    public function activities()
    {
        return $this->content->getGroupedTrackingHistorie();
    }

    #[Computed]
    public function isRejected()
    {
        return $this->currentStatus() === (new \App\States\InformationSystem\Rejected($this->content))->label();
    }

    #[Computed]
    public function currentStatus()
    {
        if ($this->content) {
            return $this->content->status->label();
        }

        return (new \App\States\InformationSystem\Pending($this->content))->label();
    }

    #[Computed]
    public function uploadedFile()
    {
        return collect($this->content->documentUploads)->map(function ($documentUpload) {
            return [
                'part_number' => $documentUpload->part_number,
                'part_number_label' => $documentUpload->part_number_label,
            ];
        });
    }

    #[Computed]
    public function linkProductions()
    {
        return collect($this->content?->links)->map(function ($url, $key) {
            $label = PublicRelationRequestPart::tryFrom((int) $key)?->label() ?? 'Unknown';

            return [
                'label' => $label,
                'url' => $url,
            ];
        })->values();
    }

    #[Computed]
    public function needUploadAdditionalFile(): bool
    {
        return $this->uploadedFile->contains(fn($file) => $file['part_number'] === 5);
    }

    public function additionalUploadFile()
    {
        $this->validate([
            'additionalFile' => ['required', 'mimes:pdf']
        ], [
            'additionalFile.required' => 'Surat perjanjian kerahasiaan harus dilampirkan!'
        ]);

        DB::transaction(function () {
            $hasPartNumber5 = $this->needUploadAdditionalFile;

            if (!$hasPartNumber5) {
                $documentUpload = $this->content->documentUploads()->create([
                    'part_number' => 5,
                    'need_revision' => false,
                ]);

                if (!empty($this->additionalFile)) {
                    $path = $this->additionalFile->store('documents', 'public');
                    $this->additionalFile->store('documents', 'public');
                    $versioning = $documentUpload->versions()->create([
                        'file_path' => $path,
                        'is_resolved' => true,
                    ]);

                    $documentUpload->update([
                        'document_upload_version_id' => $versioning->id
                    ]);

                    $this->content->trackingHistorie()->create([
                        'action' => 'Pemohon telah menambahkan dokumen pendukung',
                        'created_by' => auth()->user()->id
                    ]);
                }
            }
        });

        session()->flash('status', [
            'variant' => 'success',
            'message' => 'Dokumen pendukung berhasil dilampirkan.',
        ]);

        $this->redirectRoute('detail.request', ['type' => $this->type, 'id' => $this->id]);
    }

    public function downloadFile($typeNumber)
    {
        $document = $this->content->documentUploads()->where('part_number', $typeNumber)->with('activeVersion')->first();

        if ($document) {
            $filePath = $document->activeVersion->file_path;

            $fileDownload = Storage::disk('public')->path($filePath);

            return response()->download($fileDownload);
        }

        abort(404, 'Template not found.');
    }

    public function submitRating()
    {
        $this->validate([
            'rating.value' => 'required|numeric|in:1,2,3,4,5',
            'rating.comment' => [
                Rule::requiredIf(fn() => $this->rating['value'] <= 2),
                'nullable',
                'string',
                'max:150',
            ],
        ], [
            'rating.comment.required' => 'Beri kami masukan agar lebih baik lagi :)'
        ]);

        $rating = [
            'rating' => $this->rating['value'],
            'comment' => $this->rating['comment'],
            'rating_date' => Carbon::now()->toDateTimeString(),
            'replied_at' => null,
        ];

        DB::transaction(function () use ($rating) {
            $this->content?->update([
                'rating' => $rating,
            ]);
        });

        $this->redirectRoute('detail.request', ['type' => $this->type, 'id' => $this->id]);
    }
}
