<?php

namespace App\Livewire\Requests\InformationSystem;

use App\Enums\Division;
use App\States\InformationSystem\Pending;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\InformationSystemRequest;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\WithPagination;

class Rollback extends Component
{
    use WithPagination;

    #[Locked]
    public int $systemRequestId;
    public array $trackId = [];
    protected ?InformationSystemRequest $systemRequest = null;

    public int $perPage = 5;

    public string $changeStatus = '';
    public ?string $currentDivision = null;
    public string $deletedRecords = 'all';

    #[Title('Rollback')]
    public function render()
    {
        if (!$this->systemRequest) {
            $this->systemRequest = $this->getSystemRequest();
        }
        return view('livewire.requests.information-system.rollback', [
            'trackingHistorie' => $this->getTrackingHistorie(),
            'systemRequest' => $this->systemRequest // Pass systemRequest to view if needed
        ]);
    }

    public function mount(int $id)
    {
        $this->systemRequestId = $id;
        $this->systemRequest = $this->getSystemRequest();
        $this->currentDivision = $this->systemRequest->current_division
            ? Division::tryFrom($this->systemRequest->current_division)?->getShortLabelFromId($this->systemRequest->current_division)
            : null;
    }

    protected function getSystemRequest(): InformationSystemRequest
    {
        return InformationSystemRequest::findOrFail($this->systemRequestId);
    }

    protected function getTrackingHistorie()
    {
        if (!$this->systemRequest) {
            $this->systemRequest = $this->getSystemRequest();
        }

        return $this->systemRequest->trackingHistorie()
            // ->sortBy($this->filter['sortBy'])
            ->withDeletedRecords($this->deletedRecords)
            ->paginate($this->perPage);
    }

    #[Computed]
    public function status()
    {
        return $this->systemRequest->status;
    }

    #[Computed]
    public function currentDivisions()
    {
        return $this->systemRequest->kasatpelName($this->systemRequest->current_division);
    }

    public function save(): void
    {
        DB::transaction(function () {
            $systemRequest = InformationSystemRequest::findOrFail($this->systemRequestId);
            $hasStatusChange = !empty($this->changeStatus);
            $isNotPending = !$systemRequest->status instanceof Pending;
            $hasTrackChanges = !empty($this->trackId);

            // Early return if no changes
            if (!$hasStatusChange && !$hasTrackChanges && !$this->shouldUpdateDivision($systemRequest)) {
                return;
            }

            // Handle status change
            if ($hasStatusChange && $isNotPending) {
                $this->processStatusChange($systemRequest);
            }

            // Handle division update if not pending
            if ($isNotPending && $this->shouldUpdateDivision($systemRequest)) {
                $this->updateDivision($systemRequest);
            }

            // Handle tracking history changes
            if ($hasTrackChanges) {
                $this->processTrackingHistory($systemRequest);
            }
        });

        $this->redirectRoute('is.show', $this->systemRequestId);
    }

    protected function shouldUpdateDivision($systemRequest): bool
    {
        if (empty($this->currentDivision)) {
            return false;
        }

        $newDivisionId = Division::getIdFromString($this->currentDivision);
        return $newDivisionId !== $systemRequest->current_division;
    }

    protected function processStatusChange($systemRequest): void
    {
        $systemRequest->checkRevisionForRollback();
        $systemRequest->transitionStatusOnlyFromString($this->changeStatus);
        $systemRequest->refresh();
        $systemRequest->updateForRollback();
        $systemRequest->logStatusCustom("Telah terjadi rollback ke status: {$systemRequest->status->label()}");
    }

    protected function updateDivision($systemRequest): void
    {
        $newDivisionId = Division::getIdFromString($this->currentDivision);
        $systemRequest->update(['current_division' => $newDivisionId]);

        $message = "Permohonan layanan di pindahkan ke Kepala Satuan Pelaksana " .
            $systemRequest->kasatpelName($newDivisionId);
        $systemRequest->logStatusCustom($message);

        $oldMessage = $systemRequest->status->trackingMessage($systemRequest->current_division);
        $newMessage = $systemRequest->status->trackingMessage($newDivisionId);

        $systemRequest->trackingHistorie()
            ->where('action', $oldMessage)
            ->update(['action' => $newMessage]);
    }

    protected function processTrackingHistory($systemRequest): void
    {
        $trackingHistories = $systemRequest->trackingHistorie()
            ->withTrashed()
            ->whereIn('id', $this->trackId)
            ->get();

        $trackingHistories->each(function ($tracking) {
            $tracking->trashed() ? $tracking->restore() : $tracking->delete();
        });

        $this->trackId = [];
    }
}
