<?php

namespace App\Livewire\Requests\InformationSystem;

use Carbon\Carbon;
use App\States\InformationSystem\Process;
use App\States\InformationSystem\Completed;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\InformationSystemRequest;
use Illuminate\Database\Eloquent\Collection;
use App\Mail\Requests\InformationSystem\NeedNDAMail;
use App\Mail\Requests\InformationSystem\RevisionMail;
use Illuminate\Support\Facades\Cache;

class Show extends Component
{
    #[Locked]
    public int $systemRequestId;

    public InformationSystemRequest $systemRequest;

    protected ?Collection $tracks = null;

    public $notes = '';

    public $emailChecked = [];

    public function mount(int $id)
    {
        $this->systemRequestId = $id;
        $this->systemRequest = InformationSystemRequest::with(['documentUploads.activeVersion:id,file_path', 'user:id,name,contact,section'])->findOrFail($this->systemRequestId);
    }

    #[Title('Detail Permohonan')]
    public function render()
    {
        return view('livewire.requests.information-system.show');
    }

    #[Computed]
    public function allowedParts()
    {
        return $this->systemRequest->allowedParts();
    }

    #[Computed]
    public function timeline(): ?string
    {
        // Checking current status
        if (!in_array($this->systemRequest->status, [Process::class, Completed::class])) {
            return null;
        }

        // Load requestStatusTrack if not loaded
        if ($this->tracks === null) {
            $this->tracks = $this->systemRequest->requestStatusTrack()
                ->select('statusable_id', 'action', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(2)
                ->get();
        }

        // Find first and last track
        $firstTrack = $this->tracks->first(fn($item): bool => Str::contains($item->action, 'Permohonan layanan sedang diproses oleh'));
        $lastTrack = $this->tracks->first(fn($item): bool => Str::contains($item->action, 'Permohonan layanan telah selesai di kerjakan oleh divisi'));

        if ($firstTrack) {
            $startDate = Carbon::parse($firstTrack->created_at);
            $endDate = $lastTrack
                ? Carbon::parse($lastTrack->created_at)
                : now();

            $totalDays = $startDate->diffInDays($endDate);
            $totalHours = $startDate->diffInHours($endDate);

            // Format date range based on same month or not
            $dateRange = $startDate->isSameMonth($endDate)
                ? $startDate->format('d') . ' - ' . $endDate->format('d M Y')
                : $startDate->format('d M Y') . ' sampai ' . $endDate->format('d M Y');

            if ($totalDays < 1) {
                // If less than 1 day, display in hours
                return $lastTrack
                    ? "Selesai dalam " . round($totalHours) . " jam dari tanggal " . $startDate->format('d M Y H:i') . " sampai " . $endDate->format('H:i')
                    : "Berlangsung selama " . round($totalHours) . " jam dari tanggal " . $startDate->format('d M Y H:i');
            } else {
                // If more than or equal to 1 day, display in days
                return $lastTrack
                    ? "Selesai dalam " . round($totalDays) . " hari dari tanggal " . $dateRange
                    : "Berlangsung selama " . round($totalDays) . " hari dari tanggal " . $dateRange;
            }
        }

        return null;
    }

    public function inputNotes(): void
    {
        $systemRequest = $this->systemRequest;

        DB::transaction(function () use ($systemRequest) {

            $existingNotes = $systemRequest->notes;

            $existingNotes[] = $this->notes;

            $systemRequest->update(['notes' => $existingNotes]);

            $this->reset('notes');
        });

        $systemRequest->load('documentUploads.activeVersion');
    }

    public function sendMail()
    {
        $data = [
            'title' => $this->systemRequest->title,
            'created_at' => $this->systemRequest->createdAtDMY(),
            'url' => route('detail.request', ['type' => 'information-system', 'id' => $this->systemRequestId]),
        ];

        $email = $this->systemRequest->user->email;

        foreach ($this->emailChecked as $emailType) {
            switch ($emailType) {
                case 'need-nda':
                    Mail::to($email)->send(new NeedNDAMail($data));
                    break;

                case 'reminder-revision':
                    $revisionMailData = Cache::get("revision-mail-{$this->systemRequestId}");
                    Mail::to($email)->send(new RevisionMail($revisionMailData, 'reminder'));
                    break;

                default:
                    break;
            }
        }

        session()->flash('success', 'Email berhasil dikirim');

        $this->systemRequest->load('documentUploads.activeVersion');

        $this->dispatch('modal-close', name: 'email-modal');
    }
}
