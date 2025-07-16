<?php

namespace App\Livewire\Forms\modal\InformationSystem;

use App\Enums\InformationSystemRequestPart;
use App\States\InformationSystem\Completed;
use App\States\InformationSystem\Process;
use Livewire\Form;
use Illuminate\Support\Facades\DB;
use App\Models\InformationSystemRequest;
use App\Enums\Division;
use Illuminate\Support\Facades\Cache;

class ActionForm extends Form
{
    private InformationSystemRequest $systemRequest;

    public string $status = '';

    public string $notes = '';

    public string $selectedDivision = '';

    public array $revisionParts = [];

    public array $revisionNotes = [];

    public bool $hasPartNumber5 = false;

    private const REPLY_STATUSES = ['replied', 'replied_kapusdatin'];

    public function getSelectedDivisionId()
    {
        return Division::getIdFromString($this->selectedDivision);
    }

    public function disposition(int $systemRequestId)
    {
        $rules = [
            'status' => 'required|in:disposition,rejected',
            'notes' => 'required',
            'selectedDivision' => 'required',
        ];

        $messages = [
            'status.required' => 'Status selanjutnya tidak boleh kosong',
            'status.in' => 'Status selanjutnya tidak valid',
            'notes.required' => 'Catatan tidak boleh kosong',
            'selectedDivision.required' => 'Divisi tujuan tidak boleh kosong',
        ];

        $this->validate($rules, $messages);

        DB::transaction(function () use ($systemRequestId) {
            $systemRequest = InformationSystemRequest::findOrFail($systemRequestId);

            // Transisi status
            $systemRequest->transitionStatusFromPending(
                $this->status,
                $this->getSelectedDivisionId(),
                $this->notes
            );

            $systemRequest->refresh();

            $systemRequest->logStatus(null);

            DB::afterCommit(function () use ($systemRequest) {
                $systemRequest->sendDispositionServiceRequestNotification();
            });

            session()->flash('status', [
                'variant' => 'success',
                'message' => $systemRequest->status->toastMessage(),
            ]);
        });
    }

    protected function isReplying(): bool
    {
        return in_array($this->status, self::REPLY_STATUSES, true);
    }

    public function verification(int $systemRequestId): void
    {
        $rules = [
            'status' => 'required|string|in:approved_kasatpel,replied,approved_kapusdatin,replied_kapusdatin',
            'revisionParts' => 'required_if:status,replied,replied_kapusdatin',
        ];

        if ($this->isReplying() && !empty($this->revisionParts)) {
            $rules['revisionParts'] = 'array|min:1';
            $rules['revisionNotes'] = 'required|array';

            foreach ($this->revisionParts as $part) {
                $rules["revisionNotes.{$part}"] = 'required|string|max:255';
            }
        }

        $message = [
            'status.required' => 'Status selanjutnya tidak boleh kosong',
            'status.in' => 'Status selanjutnya tidak valid',
            'revisionParts.required_if' => 'Butuh bagian yang harus di revisi',
            'revisionParts.min' => 'Bagian yang harus di revisi minimal 1',
            'revisionNotes.required' => 'Catatan revisi wajib diisi',
            'revisionNotes.array' => 'Format catatan revisi tidak valid',
            'revisionNotes.*.required' => 'Catatan revisi wajib diisi untuk bagian yang dipilih',
            'revisionNotes.*.max' => 'Catatan revisi maksimal 255 karakter',
        ];

        $this->validate($rules, $message);

        DB::transaction(function () use ($systemRequestId) {
            $systemRequest = InformationSystemRequest::with('documentUploads')->findOrFail($systemRequestId);

            if ($this->isReplying()) {
                $this->processRevisions($systemRequest);
            }

            $this->updateSystemRequestStatus($systemRequest);
            $this->handleAfterCommitVerification($systemRequest);
        });
    }

    protected function processRevisions(InformationSystemRequest $systemRequest): array
    {
        if (empty($this->revisionParts)) {
            throw new \InvalidArgumentException('Part of revision is required, cannot be empty');
        }

        return $this->createRevisions($systemRequest);
    }

    protected function createRevisions(InformationSystemRequest $systemRequest): array
    {
        if (!$this->isReplying()) {
            return [];
        }

        $documentUploadsByPartNumber = $systemRequest->documentUploads->keyBy('part_number');
        $revisionNotes = [];

        foreach (array_filter($this->revisionParts ?? []) as $partNumber) {
            $revisionNote = trim($this->revisionNotes[$partNumber] ?? '');

            if (empty($revisionNote) || !($documentUpload = $documentUploadsByPartNumber->get($partNumber))) {
                continue;
            }

            $documentUpload->createRevision($revisionNote);
            $revisionNotes[] = [
                'part_number' => $partNumber,
                'label' => InformationSystemRequestPart::tryFrom($partNumber)->label(),
                'note' => $revisionNote
            ];
        }

        return $revisionNotes;
    }

    public function formatRevisionNotes(InformationSystemRequest $systemRequest): array
    {
        $revisions = [];

        if ($this->isReplying() && !empty($this->revisionParts)) {
            $documentUploadsByPartNumber = $systemRequest->documentUploads->keyBy('part_number');

            foreach (array_filter($this->revisionParts ?? []) as $partNumber) {
                $revisionNote = trim($this->revisionNotes[$partNumber] ?? '');

                if (empty($revisionNote) || !$documentUploadsByPartNumber->has($partNumber)) {
                    continue;
                }

                $revisions[] = [
                    'part_number' => $partNumber,
                    'label' => InformationSystemRequestPart::tryFrom($partNumber)->label(),
                    'note' => $revisionNote
                ];
            }
        }

        // Sort by part number and convert to label => note format
        return collect($revisions)
            ->sortBy('part_number')
            ->pluck('note', 'label')
            ->all();
    }

    protected function updateSystemRequestStatus(InformationSystemRequest $systemRequest): void
    {
        $systemRequest->transitionStatusFromDisposition($this->status);
        $systemRequest->refresh();
        $systemRequest->logStatus($this->notes);
    }

    protected function handleAfterCommitVerification(InformationSystemRequest $systemRequest): void
    {
        DB::afterCommit(function () use ($systemRequest) {
            $data = $this->prepareNotificationData($systemRequest);
            $systemRequest->sendProcessServiceRequestNotification($data);

            session()->flash('status', [
                'variant' => 'success',
                'message' => $systemRequest->status->toastMessage(),
            ]);
        });
    }

    protected function prepareNotificationData(InformationSystemRequest $systemRequest): array
    {
        if (!$this->isReplying()) {
            return [];
        }

        $data = [
            'title' => $systemRequest->title,
            'revision_notes' => $this->formatRevisionNotes($systemRequest),
            'url' => route('is.edit', $systemRequest->id),
        ];

        Cache::rememberForever(
            "revision-mail-{$systemRequest->id}",
            fn() => $data
        );

        return $data;
    }

    public function process(int $systemRequestId)
    {
        DB::transaction(function () use ($systemRequestId) {
            $systemRequest = InformationSystemRequest::findOrFail($systemRequestId);

            $systemRequest->status->transitionTo(Process::class);

            $systemRequest->refresh();

            $systemRequest->logStatus(null);

            DB::afterCommit(function () use ($systemRequest) {
                $systemRequest->sendProcessServiceRequestNotification();
                session()->flash('status', [
                    'variant' => 'success',
                    'message' => $systemRequest->status->toastMessage(),
                ]);
            });
        });
    }

    public function completed(int $systemRequestId): bool
    {
        $systemRequest = InformationSystemRequest::findOrFail($systemRequestId);

        // Check part number 5
        $hasPartNumber5 = $systemRequest->documentUploads()->where('part_number', InformationSystemRequestPart::NON_DISCLOSURE_AGREEMENT->value)->exists();

        if (!$hasPartNumber5) {
            // Add error hasPartNumber5 to error
            $this->addError('hasPartNumber5', InformationSystemRequestPart::NON_DISCLOSURE_AGREEMENT->label() . ' sudah diunggah oleh pemohon');

            // Return false if hasPartNumber5 is not found
            return false;
        }

        return DB::transaction(function () use ($systemRequest) {
            $systemRequest->status->transitionTo(Completed::class);
            $systemRequest->refresh();
            $systemRequest->logStatus(null);

            DB::afterCommit(function () use ($systemRequest) {
                $systemRequest->sendProcessServiceRequestNotification();

                session()->flash('status', [
                    'variant' => 'success',
                    'message' => $systemRequest->status->toastMessage(),
                ]);
            });

            // Return true if completed successfully
            return true;
        });
    }
}
