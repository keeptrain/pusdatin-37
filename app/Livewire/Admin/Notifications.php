<?php

namespace App\Livewire\Admin;

use Carbon\Carbon;
use Livewire\Component;
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

        return $this->groupNotifications($notifications);
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
        $notification = auth()->user()
            ->unreadNotifications()
            ->find($notificationId);

        if ($notification) {
            $notification->markAsRead();

            $letterId = $notification->data['id'] ?? null;

            if ($letterId) {
                return redirect()->to("/letter/$letterId");
            }
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        unset($this->notifications); 
    }
}
