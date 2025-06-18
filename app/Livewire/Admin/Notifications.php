<?php

namespace App\Livewire\Admin;

use Carbon\Carbon;
use Livewire\Component;
use App\States\Replied;
use App\Models\Letters\Letter;
use Livewire\Attributes\Computed;
use App\Models\PublicRelationRequest;

class Notifications extends Component
{
    public bool $dashboardUser = false;

    public $notificationCount = 0;

    public array $userTabs = [];

    public function mount(bool $dashboardUser = false)
    {
        $this->dashboardUser = $dashboardUser;
        $this->notificationCount = $this->emitCount();
        $this->userTabs = $this->tabBaseRoles();
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
    public function unreadNotifications()
    {
        return auth()->user()->unreadNotifications();
    }

    #[Computed]
    public function notifications()
    {
        $notifications = $this->unreadNotifications
            ->latest()
            ->take(10)
            ->get(['id', 'data', 'created_at']);

        return $this->prepareNotifications($notifications);
    }

    private function tabBaseRoles()
    {
        $user = auth()->user()->roles->pluck('name');
        $userTabs = [];

        if ($user->contains('head_verifier')) {
            $userTabs = ['all', 'disposisi', 'revisi', 'disetujui'];
        } else if ($user->contains('si_verifier|data_verifier')) {
            $userTabs = ['all', 'disposisi', 'revisi', 'disetujui'];
        } else if ($user->contains('pr_verifier')) {
            $userTabs = ['all', 'disposisi'];
        } else if ($user->contains('promkes_verifier')) {
            $userTabs = ['all'];
        } else {
            $userTabs = ['all', 'revisi', 'disetujui'];
        }

        return $userTabs;
    }

    private function prepareNotifications($notifications)
    {
        return collect($notifications)->map(function ($notification) {
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
        $count = $this->unreadNotifications->count();

        $this->dispatch('notification-count-updated', [
            'count' => $count,
        ]);
    }

    public function goDetailPage($notificationId)
    {
        // Find notification by id
        $notification = $this->unreadNotifications->where('id', $notificationId)->first();

        // Check notification data    
        if (!$notification || !isset($notification->data['requestable_type'], $notification->data['requestable_id'])) {
            return redirect()->route('dashboard')->with('error', 'Notification not found.');
        }

        try {
            // Get model class and id from notification data
            $modelClass = $notification->data['requestable_type'];
            $modelId = $notification->data['requestable_id'];

            // Validation modelClass to prevent Injection
            if (!in_array($modelClass, [Letter::class, PublicRelationRequest::class])) {
                throw new \InvalidArgumentException("Class model invalid");
            }

            // Get requestable data from available container instance
            $requestable = app($modelClass)->findOrFail($modelId);

            if ($modelClass === Letter::class || $modelClass === PublicRelationRequest::class) {
                // $notification->markAsRead();
                if (!$requestable->active_revision && $requestable->status == Replied::class && auth()->user()->currentUserRoleId() == 7) {
                    $this->flashErrorMessage();
                } else {
                    $this->redirect($requestable->handleRedirectNotification(auth()->user(), $notification->data['status']), true);
                }
            } else {
                abort(404, 'Invalid model class.');
            }
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Failed to load notification: ' . $e->getMessage());
        }
    }

    public function flashErrorMessage()
    {
        session()->flash('status', [
            'variant' => 'error',
            'message' => 'Sudah melakukan revisi pada permohonan ini.',
        ]);

        $this->redirectRoute('dashboard', navigate: true);
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        unset($this->notifications);
    }
}
