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
        $content = $this->loadContent()->get();
        $successCount = 0;
        $failCount = 0;
        $alreadyRepliedCount = 0;
        $nothingToReply = false;

        foreach ($content as $item) {
            // Skip jika email tidak ada atau rating tidak valid
            if (empty($item->rating)) {
                $nothingToReply = true;
                continue;
            }

            // Skip jika sudah pernah dibalas
            if (!empty($item->rating['replied_at'])) {
                $alreadyRepliedCount++;
                continue;
            }

            try {
                Mail::to($item->user->email)->send(new ReplyAssessmentMail($item->rating['rating']));

                // Update hanya replied_at
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

        // Prepare status message based on different scenarios
        $message = $this->prepareStatusMessage(
            $successCount,
            $failCount,
            $alreadyRepliedCount,
            $nothingToReply
        );

        session()->flash('status', [
            'variant' => $successCount > 0 ? 'success' : 'info',
            'message' => $message,
        ]);

        $this->redirectRoute('show.ratings', navigate: true);
    }

    protected function prepareStatusMessage(
        int $successCount,
        int $failCount,
        int $alreadyRepliedCount,
        bool $nothingToReply
    ): string {
        $messages = [];

        if ($successCount > 0) {
            $messages[] = "Berhasil mengirim {$successCount} email.";
        }

        if ($failCount > 0) {
            $messages[] = "Gagal mengirim {$failCount} email.";
        }

        if ($alreadyRepliedCount > 0) {
            $messages[] = "{$alreadyRepliedCount} email sudah pernah dikirim sebelumnya.";
        }

        if ($nothingToReply) {
            $messages[] = "Tidak ada rating lain yang perlu dibalas.";
        }

        // Special case when nothing was sent
        if ($successCount === 0 && $failCount === 0 && $alreadyRepliedCount === 0) {
            return "Semua balasan email telah dikirim sebelumnya.";
        }

        return implode(' ', $messages);
    }
}
