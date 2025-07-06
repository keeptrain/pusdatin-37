<?php

namespace App\Livewire\Requests;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\InformationSystemRequest;
use App\Models\PublicRelationRequest;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReplyAssessmentMail;

#[Title('Penilaian Layanan')]
class ShowRatings extends Component
{
    use WithPagination;

    public string $sortDirection = 'asc';
    public string $sortField = 'rating';

    // private $contents;

    public function mount()
    {
        // $this->contents = $this->loadContent();
    }

    public function render()
    {
        $contents = $this->loadContent()->simplePaginate(10);
        $ratingCount = $this->ratingCount($contents);

        return view('livewire.requests.show-ratings', compact('ratingCount', 'contents'));
    }

    protected function ratingCount($loadContent)
    {
        return collect($loadContent->items())->filter(function ($item) {
            return !empty(data_get($item->rating, 'rating'));
        })->count();
    }

    protected function loadContent()
    {
        $roleId = auth()->user()->currentUserRoleId();

        return match ($roleId) {
            3, 4 => $this->systemRequests($roleId),
            5 => $this->prRequests(),
            default => abort(404, 'Invalid content type.')
        };
    }

    public function systemRequests(int $division)
    {
        return InformationSystemRequest::select('id', 'user_id', 'current_division', 'title', 'rating')
            ->with('user:id,name,email')
            ->where('current_division', $division)
            ->orderByRaw("(rating->>'$.rating') " . ($this->sortDirection === 'asc' ? 'ASC' : 'DESC'))
            ->orderBy('updated_at', 'desc');
    }

    public function prRequests()
    {
        return PublicRelationRequest::select('id', 'user_id', 'theme', 'rating')
            ->with('user:id,name,email')
            ->orderByRaw("(rating->>'$.rating') " . ($this->sortDirection === 'asc' ? 'ASC' : 'DESC'))
            ->orderBy('updated_at', 'desc');
    }

    public function sortRating($direction)
    {
        $this->sortDirection = $direction;
    }

    public function replyToAllGivesRating()
    {
        $contents = $this->loadContent()->get();
        $successCount = 0;
        $failCount = 0;
        $alreadyRepliedCount = 0;
        $invalidCount = 0;
        $totalProcessed = 0;

        foreach ($contents as $item) {
            $totalProcessed++;

            // Skip if rating not valid
            if (empty($item->rating)) {
                $invalidCount++;
                continue;
            }

            // Skip if already replied
            if (!empty($item->rating['replied_at'])) {
                $alreadyRepliedCount++;
                continue;
            }

            try {
                Mail::to($item->user->email)->send(new ReplyAssessmentMail($item->rating['rating']));

                $rating = $item->rating;
                $rating['replied_at'] = now()->toDateTimeString();
                $item->rating = $rating;
                $item->save();

                $successCount++;
            } catch (\Exception $e) {
                \Log::error("Failed to send email to {$item->user->email}: " . $e->getMessage());
                $failCount++;
            }
        }

        $message = $this->prepareStatusMessage(
            totalItems: count($contents),
            successCount: $successCount,
            failCount: $failCount,
            alreadyRepliedCount: $alreadyRepliedCount,
            invalidCount: $invalidCount
        );

        session()->flash('status', [
            'variant' => $successCount > 0 ? 'success' : 'info',
            'message' => $message,
        ]);

        $this->redirectRoute('show.ratings', navigate: true);
    }

    protected function prepareStatusMessage(
        int $totalItems,
        int $successCount,
        int $failCount,
        int $alreadyRepliedCount,
        int $invalidCount
    ): string {
        // Special case 1: All items were already replied
        if ($alreadyRepliedCount === $totalItems) {
            return "Semua balasan email telah dikirim sebelumnya.";
        }

        // Special case 2: No valid items to process
        if ($invalidCount === $totalItems) {
            return "Tidak ada rating valid yang perlu dibalas.";
        }

        // Normal cases
        $messages = [];
        if ($successCount > 0)
            $messages[] = "Berhasil mengirim {$successCount} email.";
        if ($failCount > 0)
            $messages[] = "Gagal mengirim {$failCount} email.";
        if ($alreadyRepliedCount > 0)
            $messages[] = "{$alreadyRepliedCount} sudah pernah dikirim.";
        if ($invalidCount > 0)
            $messages[] = "{$invalidCount} data tidak valid.";

        return implode(' ', $messages);
    }
}
