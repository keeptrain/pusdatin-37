<?php

namespace App\Livewire\Documents;

use Livewire\Component;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Documents\DocumentUpload;
use App\Models\Documents\UploadVersion;
use App\Models\InformationSystemRequest;

class Review extends Component
{
    #[Locked]
    public int $systemRequestId;

    public $informationSystemRequest;

    public $currentVersions;

    public $latestRevisions;

    public $changesChoice = '';

    public array $partAccepted = [];

    public $note = '';

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
        $this->systemRequestId = $id;
        $this->informationSystemRequest = $this->getSystemRequests();
        $this->processVersions();
    }

    public function getSystemRequests()
    {
        return InformationSystemRequest::select('id')->with([
            'documentUploads.activeVersion',
            'documentUploads.versions'
        ])->findOrFail($this->systemRequestId);
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $systemRequest = $this->informationSystemRequest;

            $documentUploadsIds = $systemRequest->documentUploads()->pluck('id');

            if ($this->changesChoice === 'yes') {
                $this->changesChoiceYes($systemRequest, $documentUploadsIds);
            } elseif ($this->changesChoice === 'no') {
                $this->changesChoiceNo($documentUploadsIds);
            }

            if (!empty($this->note)) {
                $systemRequest->logStatusReview("Kepala Satuan Pelaksana telah melakukan review dan memberikan", $this->note);
            } else {
                $systemRequest->logStatusCustom("Kepala Satuan Pelaksana telah melakukan review");
            }

            $systemRequest->updatedForCompletedReview();

            session()->flash('status', [
                'variant' => 'success',
                'message' => 'Berhasil melakukan review'
            ]);

            $this->redirectRoute('is.show', ['id' => $this->systemRequestId]);
        });
    }

    private function changesChoiceYes(InformationSystemRequest $systemRequest, $documentUploadIds)
    {
        // Proses file yang dipilih ($this->partAccepted)
        $selectedDocumentUploads = DocumentUpload::whereIn('part_number', $this->partAccepted)
            ->where('documentable_id', $systemRequest->id)
            ->get();

        // Update versi terbaru yang belum disetujui (is_resolved = false) untuk file yang dipilih
        $selectedDocumentUploads->each(function ($documentUpload) {
            // Ambil revisi terbaru yang belum disetujui untuk dokumen ini
            $latestUnapprovedRevision = UploadVersion::where('document_upload_id', $documentUpload->id)
                ->where('is_resolved', false)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($latestUnapprovedRevision) {
                $documentUpload->update([
                    'document_upload_version_id' => $latestUnapprovedRevision->id,
                    'need_revision' => false,
                ]);

                UploadVersion::where('id', $latestUnapprovedRevision->id)
                    ->update([
                        'is_resolved' => true,
                    ]);
            }
        });

        // Proses file yang tidak dipilih
        $unselectedDocumentUploadIds = $documentUploadIds->diff($selectedDocumentUploads->pluck('id'));

        UploadVersion::whereIn('document_upload_id', $unselectedDocumentUploadIds)
            ->update([
                'is_resolved' => true,
            ]);

        // Perbarui file_path untuk bagian yang dipilih
        $selectedDocumentUploads->each(function ($documentUpload) {
            $documentUpload->update([
                'file_path' => $this->newFilePath[$documentUpload->part_number] ?? $documentUpload->file_path,
            ]);
        });
    }

    private function changesChoiceNo($documentUploadIds)
    {
        DocumentUpload::whereIn('id', $documentUploadIds)->update([
            'need_revision' => false,
        ]);

        UploadVersion::whereIn('document_upload_id', $documentUploadIds)
            ->update([
                'is_resolved' => true,
            ]);
    }

    private function processVersions()
    {
        $currentVersionsData = new Collection();
        $latestUnapprovedRevisionsData = new Collection();

        if ($this->informationSystemRequest && $this->informationSystemRequest->documentUploads->isNotEmpty()) {
            foreach ($this->informationSystemRequest->documentUploads as $documentUpload) {
                if ($documentUpload->hasUnapprovedRevision()) {
                    $latestUnapprovedRevisionsData->push($documentUpload->formatForLatestUnapprovedRevision());

                    $currentVersionsData->push($documentUpload->formatForCurrentVersion());
                }
            }
        }

        // Urutkan data berdasarkan part_number
        $this->currentVersions = $currentVersionsData->sortBy('part_number');
        $this->latestRevisions = $latestUnapprovedRevisionsData->sortBy('part_number');
    }
}
