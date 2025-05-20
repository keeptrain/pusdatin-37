<?php

namespace App\Livewire\Documents;


use App\Models\User;
use Livewire\Component;
use App\Models\Letters\Letter;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Documents\DocumentUpload;
use App\Models\Documents\UploadVersion;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewServiceRequestNotification;

class Review extends Component
{
    #[Locked]
    public int $letterId;

    public $letter;

    public $currentVersions;

    public $latestRevisions;

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
            'changesChoice.required' => 'Pilih persetujuan perubahan.',
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
        return Letter::select('id')->with([
            'documentUploads'
        ])->findOrFail($this->letterId);
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            if ($this->changesChoice === 'yes') {
                $this->changesChoiceYes();
            } elseif ($this->changesChoice === 'no') {
                $this->changesChoiceNo();
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

    private function changesChoiceYes()
    {
        // Ambil semua document uploads dari letter
        $documentUploadIds = $this->letter->documentUploads()->pluck('id');

        // Proses file yang dipilih ($this->partAccepted)
        $selectedDocumentUploads = DocumentUpload::whereIn('part_number', $this->partAccepted)
            ->where('documentable_id', $this->letter->id)
            ->get();

        // Update versi terbaru yang belum disetujui (is_resolved = false) untuk file yang dipilih
        $selectedDocumentUploads->each(function ($documentUpload) {
            // Ambil revisi terbaru yang belum disetujui untuk dokumen ini
            $latestUnapprovedRevision = UploadVersion::where('document_upload_id', $documentUpload->id)
                ->where('is_resolved', false)
                ->orderBy('created_at', 'desc') // Urutkan berdasarkan created_at
                ->first(); // Ambil satu revisi terbaru

            if ($latestUnapprovedRevision) {
                // Update document upload dengan revisi terbaru
                $documentUpload->update([
                    'document_upload_version_id' => $latestUnapprovedRevision->id,
                    'need_revision' => false, // Tidak memerlukan revisi lagi karena sudah dipilih
                ]);

                // Setel versi ini menjadi resolved
                UploadVersion::where('id', $latestUnapprovedRevision->id)
                    ->update([
                        'is_resolved' => true,
                    ]);
            }
        });

        // Proses file yang tidak dipilih
        $unselectedDocumentUploadIds = $documentUploadIds->diff($selectedDocumentUploads->pluck('id'));

        // Setel semua versi menjadi resolved untuk file yang tidak dipilih
        UploadVersion::whereIn('document_upload_id', $unselectedDocumentUploadIds)
            ->update([
                'is_resolved' => true,
            ]);

        // Update status letter
        $this->letter->update([
            'active_revision' => false,
            'need_review' => false,
        ]);

        // Perbarui file_path untuk bagian yang dipilih
        $selectedDocumentUploads->each(function ($documentUpload) {
            $documentUpload->update([
                'file_path' => $this->newFilePath[$documentUpload->part_number] ?? $documentUpload->file_path,
            ]);
        });
    }

    public function changesChoiceNo()
    {
        // Ambil ID dari semua document uploads milik letter ini
        $documentUploadIds = $this->letter->documentUploads()->pluck('id');

        // Update semua document uploads: set need_revision menjadi false
        DocumentUpload::whereIn('id', $documentUploadIds)->update([
            'need_revision' => false,
        ]);

        // Update semua versi dari document uploads: set is_resolved menjadi true
        DB::table('document_upload_versions')
            ->whereIn('document_upload_id', $documentUploadIds)
            ->update([
                'is_resolved' => true,
            ]);

        // Update status letter
        $this->letter->update([
            'active_revision' => false,
            'need_review' => false,
        ]);
    }

    private function processVersions()
    {
        $currentVersionsData = new Collection();
        $latestUnapprovedRevisionsData = new Collection();

        if ($this->letter && $this->letter->documentUploads->isNotEmpty()) {
            foreach ($this->letter->documentUploads as $map) {
                if ($map) {
                    $documentUpload = $map;

                    $activeVersion = $documentUpload->activeVersion;
                    $allVersions = $documentUpload->versions;

                    $currentVersionsData->push([
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

        $this->currentVersions = $currentVersionsData->sortBy('part_number');
        $this->latestRevisions = $latestUnapprovedRevisionsData->sortBy('part_number');
    }

    public function handleNotification($recipientId, $letter)
    {
        $user = User::findOrFail($recipientId);

        Notification::sendNow($user, new NewServiceRequestNotification($letter));
    }
}
