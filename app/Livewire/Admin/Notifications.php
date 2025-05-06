<?php

namespace App\Livewire\Admin;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Notifications extends Component
{
    public $notifications = [];

    public $groupedNotifications = [];

    public $notificationCount = 0;

    public function mount()
    {
        $this->notificationCount = $this->emitCount();
    }

    public function loadNotifications(bool $refresh = false)
    {
        $user = Auth::user();

        if ($refresh) {
            $user->unsetRelation('unreadNotifications');
        }

        $this->notifications = $user->unreadNotifications()
            ->latest()
            ->take(10)
            ->get(['id', 'data', 'created_at']);

        $this->groupNotifications();
    }

    public function reloadNotifications()
    {
        $this->loadNotifications(true);
    }

    protected function groupNotifications()
    {
        $this->groupedNotifications = [
            'Today' => [],
            'Yesterday' => [],
            'Earlier' => [],
        ];

        foreach ($this->notifications as $notification) {
            $createdAt = Carbon::parse($notification->created_at);

            if ($createdAt->isToday()) {
                $this->groupedNotifications['Today'][] = $notification;
            } elseif ($createdAt->isYesterday()) {
                $this->groupedNotifications['Yesterday'][] = $notification;
            } else {
                $this->groupedNotifications['Earlier'][] = $notification;
            }
        }
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
        $this->loadNotifications();
    }
}
