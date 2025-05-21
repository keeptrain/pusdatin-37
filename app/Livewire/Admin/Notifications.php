<?php

namespace App\Livewire\Admin;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Letters\Letter;
use App\Models\PublicRelationRequest;
use Livewire\Attributes\Computed;

class Notifications extends Component
{
    public $notificationCount = 0;

    public function mount()
    {
        $this->notificationCount = $this->emitCount();
    }

    #[Computed]
    public function notifications()
    {
        $notifications = auth()->user()
            ->unreadNotifications()
            ->latest()
            ->take(10)
            ->get(['id', 'data', 'created_at']);

        return $this->groupNotifications(notifications: $notifications);
    }

    protected function groupNotifications($notifications)
    {
        return $notifications->groupBy(function ($notification) {
            $createdAt = Carbon::parse($notification->created_at);

            return match (true) {
                $createdAt->isToday() => 'Today',
                $createdAt->isYesterday() => 'Yesterday',
                default => 'Earlier'
            };
        })->mapWithKeys(fn($items, $key) => [$key => $items->all()]);
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
            ->find($notificationId);

        if (!$notification || !isset($notification->data['requestable'], $notification->data['requestable_id'])) {
            return redirect()->route('dashboard')->with('error', 'Notifikasi tidak valid.');
        }

        try {
            $modelClass = $notification->data['requestable'];
            $modelId = $notification->data['requestable_id'];

            // Validasi model class untuk mencegah injection
            if (!in_array($modelClass, [Letter::class, PublicRelationRequest::class])) {
                throw new \InvalidArgumentException("Model class tidak valid.");
            }

            // Ambil data requestable dengan query efisien
            $requestable = app($modelClass)->findOrFail($modelId);

            if ($modelClass === Letter::class) {
                $notification->markAsRead();
                return $this->redirect("/letter/$requestable->id", true);
            } elseif ($modelClass === PublicRelationRequest::class) {
                $notification->markAsRead();
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
