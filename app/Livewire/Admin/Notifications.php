<?php

namespace App\Livewire\Admin;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Letters\Letter;
use Livewire\Attributes\Computed;
use App\Models\PublicRelationRequest;

class Notifications extends Component
{
    public $notificationCount = 0;

    public array $userTabs = [];

    public function mount()
    {
        $this->notificationCount = $this->emitCount();
        $this->userTabs =  $this->tabBaseRoles();
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

    private function tabBaseRoles()
    {
        $user = auth()->user();
        $userTabs = [];

        if ($user->hasRole('head_verifier')) {
            $userTabs = ['all', 'disposisi', 'revisi', 'disetujui'];
        } else if ($user->hasRole('si_verifier|data_verifier')) {
            $userTabs = ['all', 'disposisi', 'revisi'];
        } else if ($user->hasRole('pr_verifier')) {
            $userTabs = ['all', 'disposisi'];
        } else if ($user->hasRole('promkes_verifier')) {
            $userTabs = ['all'];
        } else {
            $userTabs = ['all', 'revisi', 'disetujui'];
        }

        return $userTabs;
    }

    private function prepareNotifications($rawNotifications)
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
                $createdAt->isToday() => 'Hari ini',
                $createdAt->isYesterday() => 'Kemarin',
                default => 'Terdahulu',
            };
        })->mapWithKeys(function ($items, $dateLabel) {
            return [
                $dateLabel => $items->groupBy('status')->mapWithKeys(function ($statusItems, $status) {
                    return [$status => $statusItems];
                }),
            ];
        });
    }

    private function getFilteredNotifications(array $allowedStatuses)
    {
        return collect($this->notifications)->mapWithKeys(function ($statuses, $dateLabel) use ($allowedStatuses) {
            $filteredStatuses = collect($statuses)->filter(function ($items, $status) use ($allowedStatuses) {
                return in_array($status, $allowedStatuses);
            });

            return [$dateLabel => $filteredStatuses];
        })->filter(fn($statuses) => $statuses->isNotEmpty())->toArray();
    }

    #[Computed]
    public function getFilteredDispositionNotifications()
    {
        return $this->getFilteredNotifications(['Permohonan Masuk', 'Didisposisikan', 'Kurasi Promkes', 'Proses Pusdatin']);
    }

    #[Computed]
    public function getFilteredRepliedNotifications()
    {
        return $this->getFilteredNotifications(['Revisi Kasatpel', 'Revisi Kapusdatin']);
    }

    #[Computed]
    public function getFilteredApprovedNotifications()
    {
        return $this->getFilteredNotifications(['Disetujui Kasatpel', 'Disetujui Kapusdatin', 'Permohonan Selesai']);
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

            // Ambil data requestable
            $requestable = app($modelClass)->findOrFail($modelId);

            if ($modelClass === Letter::class || $modelClass === PublicRelationRequest::class) {
                // $notification->markAsRead();
                return $this->redirect($requestable->handleRedirectNotification(auth()->user()), true);
            } else {
                abort(404, 'Invalid model class.');
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
