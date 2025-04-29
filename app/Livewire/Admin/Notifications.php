<?php

namespace App\Livewire\Admin;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;

class Notifications extends Component
{
    public $notifications = [];

    public $groupedNotifications = [];

    public function mount()
    {
        // $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $this->notifications = Auth::user()
            ->unreadNotifications()
            ->latest()
            ->take(10)
            ->get();

        $this->groupNotifications();
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

    public function goDetailPage($notificationId)
    {
        $notification = Auth::user()
            ->unreadNotifications()
            ->find($notificationId);

        if ($notification) {
            // $notification->markAsRead(); // tandai sudah dibaca

            // ambil letter_id dari data notifikasi
            $letterId = $notification->data['id'] ?? null;

            if ($letterId) {
                return redirect()->to("/letter/$letterId");
            }
        }
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        $this->loadNotifications();
    }
}
