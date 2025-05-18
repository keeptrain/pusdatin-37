<?php

namespace App\Livewire\Documents;

use App\Models\Letters\DocumentUpload;
use App\Models\User;
use Livewire\Component;
use App\Models\Letters\Letter;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Letters\LetterDirect;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewServiceRequestNotification;

class Review extends Component
{
    public int $letterId;

    public $letter;

    public $previousVersions;

    public $latestRevisions;

    public $revisionNote;

    public $activeReview;

    public $changesChoice = '';

    public array $partAccepted = [];

    public function rules()
    {
        $rules = [
            'changesChoice' => ['required', Rule::in(['yes', 'no'])],
        ];

        if ($this->changesChoice === 'yes') {
            $rules['partAccepted'] = ['required'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'changesChoice.required' => 'Pilih persetujuan perubahan',
            'partAccepted.required' => 'Membutuhkan bagian perubahan.'
        ];
    }

    public function mount(int $id)
    {
        $this->letterId = $id;
        $this->letter = $this->getLetterWithMappedData();
        $this->processVersions();
    }

    public function getLetterWithMappedData()
    {
        return Letter::with([
            'mapping.letterable' => function ($morphTo) {
                $morphTo->morphWith([
                    DocumentUpload::class => [
                        'version' => function ($query) {
                            $query->orderBy('version', 'desc');
                        }
                    ],
                    LetterDirect::class => [],
                ]);
            }
        ])->findOrFail($this->letterId);
    }

    public function save()
    {
        $this->validate();
        DB::transaction(function () {
            if ($this->changesChoice === 'yes') {
                $this->letter->mapping()
                    ->get()
                    ->filter(function ($map) {
                        return $map->letterable instanceof DocumentUpload &&
                            in_array($map->letterable->part_number, $this->partAccepted);
                    })
                    ->each(function ($mapping) {
                        /** @var DocumentUpload $documentUpload */
                        $documentUpload = $mapping->letterable;

                        $allVersions = $documentUpload->version();

                        $latestUnapprovedRevision = $allVersions
                            ->where('is_resolved', false)
                            ->first();

                        if ($latestUnapprovedRevision) {
                            $documentUpload->update([
                                'document_upload_version_id' => $latestUnapprovedRevision->id,
                                'need_revision' => false,
                            ]);

                            $allVersions->update([
                                'is_resolved' => true
                            ]);
                        }
                    });
                $this->letter->update([
                    'active_revision' => false,
                    'need_review' => false
                ]);
            } elseif ($this->changesChoice === 'no') {
                $this->letter->mapping()
                    ->get()
                    ->filter(function ($map) {
                        return $map->letterable instanceof DocumentUpload;
                    })
                    ->each(function ($mapping) {
                        /** @var DocumentUpload $documentUpload */
                        $documentUpload = $mapping->letterable;

                        $documentUpload->update([
                            'need_revision' => true,
                        ]);
                    });
                $this->letter->update([
                    'active_revision' => true,
                    'need_review' => false
                ]);
            }

            $this->handleNotification($this->letter->user_id, $this->letter);

            // Redirect dengan pesan sukses
            return redirect()->to("/letter/$this->letterId")
                ->with('status', [
                    'variant' => 'success',
                    'message' => $this->letter->status->toastMessage(),
                ]);
        });
    }

    private function processVersions()
    {
        $previousVersionsData = new Collection();
        $latestUnapprovedRevisionsData = new Collection();

        if ($this->letter && $this->letter->mapping->isNotEmpty()) {
            foreach ($this->letter->mapping as $map) {
                if ($map->letterable_type === DocumentUpload::class && $map->letterable) {
                    $documentUpload = $map->letterable;

                    $activeVersion = $documentUpload->activeVersion->first();
                    $allVersions = $documentUpload->getRelation('version');

                    $previousVersionsData->push([
                        'part_number' => $documentUpload->part_number,
                        'part_number_label' => $documentUpload->part_number_label,
                        'file_path' => $activeVersion->file_path,
                        'revision_note' => $allVersions->first()->revision_note,
                    ]);

                    $latestUnapprovedRevision = $allVersions
                        ->where('is_resolved', false)
                        ->sortByDesc('version')
                        ->first();

                    if ($latestUnapprovedRevision) {
                        $latestUnapprovedRevisionsData->push([
                            'part_number' => $documentUpload->part_number,
                            'part_number_label' => $documentUpload->part_number_label,
                            'file_path' => $latestUnapprovedRevision->file_path,
                            'revision_note' => $latestUnapprovedRevision->revision_note
                        ]);
                    };
                }
            }
        }

        $this->previousVersions = $previousVersionsData->sortBy('part_number');
        $this->latestRevisions = $latestUnapprovedRevisionsData->sortBy('part_number');
    }

    public function handleNotification($recipientId, $letter)
    {
        $user = User::findOrFail($recipientId);

        Notification::sendNow($user, new NewServiceRequestNotification($letter));
    }
}
