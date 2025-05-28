<?php

namespace App\Livewire\Admin;

use Carbon\Carbon;
use Livewire\Component;
use App\States\Disposition;
use App\Models\Letters\Letter;
use Livewire\Attributes\Computed;
use App\Models\PublicRelationRequest;
use App\States\PublicRelation\PromkesComplete;

class Notifications extends Component
{
    public $notificationCount = 0;

    public function mount()
    {
        $this->notificationCount = $this->emitCount();
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div class="relative min-h-screen">
            <div class="absolute inset-0 flex items-center justify-center">
               <flux:icon.loading />
            </div>
        </div>
        HTML;
    }

    #[Computed]
    public function notifications()
    {
        $notifications = auth()->user()
            ->unreadNotifications()
            ->latest()
            ->take(10)
            ->get(['id', 'data', 'created_at']);

        return $this->prepareNotifications($notifications);
    }

    protected function prepareNotifications($rawNotifications)
    {
        return collect($rawNotifications)->map(function ($notification) {
            return [
                'id' => $notification->id,
                'username' => $notification->data['username'] ?? 'Unknown',
                'status' => $notification->data['status'] ?? 'Unknown',
                'message' => $notification->data['message'] ?? 'No message',
                'created_at' => Carbon::parse($notification->created_at)->diffForHumans(),
            ];
        })->groupBy(function ($notification) {
            $createdAt = Carbon::parse($notification['created_at']);
    
            return match (true) {
                $createdAt->isToday() => 'Today',
                $createdAt->isYesterday() => 'Yesterday',
                default => 'Earlier',
            };
        })->mapWithKeys(function ($items, $dateLabel) {
            return [
                $dateLabel => $items->groupBy('status')->mapWithKeys(function ($statusItems, $status) {
                    return [$status => $statusItems];
                }),
            ];
        });
    }

    public function refreshNotifications()
    {
        auth()->user()->unsetRelation('unreadNotifications');
    }

    public function emitCount(): void
    {
        $count = auth()->user()
            ->unreadNotifications()
            ->count();

        $this->dispatch('notification-count-updated', [
            'count' => $count,
        ]);
    }

    public function goDetailPage($notificationId)
    {
        // Ambil notifikasi berdasarkan ID
        $notification = auth()->user()
            ->unreadNotifications()
            ->findOrFail($notificationId);

        if (!$notification || !isset($notification->data['requestable_type'], $notification->data['requestable_id'])) {
            return redirect()->route('dashboard')->with('error', 'Notifikasi tidak valid.');
        }

        try {
            $modelClass = $notification->data['requestable_type'];
            $modelId = $notification->data['requestable_id'];

            // Validasi model class untuk mencegah injection
            if (!in_array($modelClass, [Letter::class, PublicRelationRequest::class])) {
                throw new \InvalidArgumentException("Model class tidak valid.");
            }

            // Ambil data requestable dengan query
            $requestable = app($modelClass)->findOrFail($modelId);

            if ($modelClass === Letter::class) {
                // $notification->markAsRead();
                if ($requestable->status instanceof Disposition && auth()->user()->hasRole('si_verifier|data_verifier|pr_verifier')) {
                    $requestable->transitionStatusToProcess($requestable->current_division);
                    $requestable->logStatus(null);
                }

                return $this->redirect("/letter/$requestable->id", true);
            } elseif ($modelClass === PublicRelationRequest::class) {
                // $notification->markAsRead();
                if (auth()->user()->can('queue pr pusdatin') && $requestable->status instanceof PromkesComplete) {
                    $requestable->transitionStatusToPusdatinQueue();
                    $requestable->logStatus(null);
                }
                return $this->redirect("/public-relation/$requestable->id", true);
            }
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Gagal memuat detail notifikasi: ' . $e->getMessage());
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        unset($this->notifications);
    }
}
