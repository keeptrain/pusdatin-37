<?php

namespace App\Livewire\Requests;

use App\Enums\Division;
use DB;
use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\InformationSystemRequest;
use App\Models\PublicRelationRequest;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReplyAssessmentMail;

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

    #[Title('Penilaian Layanan')]
    public function render()
    {
        $contents = $this->loadContent()->simplePaginate(10);
        $ratingCount = $this->ratingCount($contents);

        return view('livewire.requests.show-ratings', compact('ratingCount', 'contents'));
    }

    protected function loadContent()
    {
        $roleId = auth()->user()->currentUserRoleId();

        return match ($roleId) {
            Division::SI_ID->value, Division::DATA_ID->value => $this->systemRequests($roleId),
            Division::PR_ID->value => $this->prRequests(),
            default => abort(404, 'Invalid content type.')
        };
    }

    protected function ratingCount($loadContent)
    {
        return collect($loadContent->items())->filter(function ($item) {
            return !empty(data_get($item->rating, 'rating'));
        })->count();
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
        // Get all contents that have rating based on division
        $contents = $this->loadContent()->get();

        $successCount = 0;
        $alreadyRepliedCount = 0;
        $emailsToSend = [];
        $itemsToUpdate = [];

        // First, collect all items that need processing
        foreach ($contents as $item) {
            if (empty($item->rating)) {
                continue;
            }

            if (!empty($item->rating['replied_at'])) {
                $alreadyRepliedCount++;
                continue;
            }

            $emailsToSend[] = [
                'name' => $item->user->name,
                'email' => $item->user->email,
                'rating' => $item->rating['rating']
            ];

            $itemsToUpdate[] = $item;
        }

        // Process all updates in a single transaction
        try {
            DB::beginTransaction();

            $successCount = 0;
            $now = now()->toDateTimeString();

            foreach ($itemsToUpdate as $index => $item) {
                try {
                    // Send email
                    Mail::to($emailsToSend[$index]['email'])
                        ->send(new ReplyAssessmentMail(
                            $emailsToSend[$index]['name'],
                            $emailsToSend[$index]['rating']
                        ));

                    // Update item
                    $rating = $item->rating;
                    $rating['replied_at'] = $now;
                    $item->rating = $rating;
                    $item->save();

                    $successCount++;
                } catch (\Exception $e) {
                    \Log::error("Failed to process email for user {$emailsToSend[$index]['email']}: " . $e->getMessage());
                    // Continue with next item even if one fails
                    continue;
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Transaction failed: " . $e->getMessage());
            session()->flash('status', [
                'variant' => 'error',
                'message' => "Terjadi kesalahan saat memproses email.",
            ]);
            return;
        }

        $totalToSend = $successCount + $alreadyRepliedCount;
        $message = $this->prepareStatusMessage($totalToSend, $successCount, $alreadyRepliedCount);

        session()->flash('status', [
            'variant' => $successCount > 0 ? 'success' : 'info',
            'message' => $message,
        ]);

        $this->redirectRoute('show.ratings', navigate: true);
    }

    protected function prepareStatusMessage(int $totalToSend, int $successCount, int $alreadyRepliedCount): string
    {
        if ($alreadyRepliedCount > 0 && $successCount === 0) {
            return "Semua email ($alreadyRepliedCount) sudah pernah dikirim.";
        }

        if ($successCount > 0) {
            $message = "Berhasil mengirim $successCount email.";
            if ($alreadyRepliedCount > 0) {
                $message .= " ($alreadyRepliedCount email sudah pernah dikirim sebelumnya)";
            }
            return $message;
        }

        return "Tidak ada email yang perlu dikirim.";
    }
}
