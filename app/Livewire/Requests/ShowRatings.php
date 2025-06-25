<?php

namespace App\Livewire\Requests;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\InformationSystemRequest;
use App\Models\PublicRelationRequest;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReplyAssessmentMail;

#[Title('Penilaian Layanan')]
class ShowRatings extends Component
{
    use WithPagination;

    public string $sortDirection = 'asc';
    public string $sortField = 'rating';

    public function mount()
    {
    }

    public function render()
    {
        return view('livewire.requests.show-ratings', [
            'contents' => $this->loadContent->simplePaginate(10)
        ]);
    }

    #[Computed]
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
        $content = $this->loadContent->get();
        $successCount = 0;
        $failCount = 0;

        foreach ($content as $item) {
            // Skip jika email tidak ada atau rating tidak valid
            if (!isset($item->user->email) || !isset($item->rating['rating'])) {
                $failCount++;
                continue;
            }

            // Skip jika sudah pernah dibalas (replied_at sudah ada)
            if (!empty($item->rating['replied_at'])) {
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

        session()->flash('status', [
            'variant' => 'success',
            'message' => "Berhasil mengirim {$successCount} email. " .
                ($failCount > 0 ? "{$failCount} gagal dikirim." : ""),
        ]);

        $this->redirectRoute('show.ratings', navigate: true);
    }
}
