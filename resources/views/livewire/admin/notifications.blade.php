<section>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-semibold text-gray-800">Notifications</h1>
            </div>
            <div class="flex space-x-2">
                <flux:modal.close>
                    <flux:button icon="x-mark" />
                </flux:modal.close>
            </div>
        </div>

        <!-- Tab navigation -->
        <div class="flex bg-zinc-50 rounded-lg p-1">
            <flux:dropdown>
                <flux:button icon:trailing="ellipsis-vertical" variant="ghost"></flux:button>

                <flux:menu>
                    <flux:menu.radio.group>
                        <flux:menu.radio checked>Latest activity</flux:menu.radio>
                        <flux:menu.radio>Date created</flux:menu.radio>
                        <flux:menu.radio>Most popular</flux:menu.radio>
                    </flux:menu.radio.group>
                </flux:menu>
            </flux:dropdown>
            <div class="ml-auto flex items-center">
                <flux:button wire:click="markAllAsRead" icon="check" variant="ghost"
                    class="flex items-center text-blue-600">
                    Mark all as read
                </flux:button>
            </div>
        </div>

        <!-- Notification list -->
        <div wire:poll.visible>
            @forelse ($this->notifications as $dateLabel => $items)
                <div class="px-4 py-2 bg-zinc-50 border-b border-gray-200 mt-4">
                    <h3 class="text-sm font-medium text-gray-500">{{ $dateLabel }}</h3>
                </div>
                @foreach ($items as $item)
                    <flux:notification.notification-adapter :notification="$item" />
                @endforeach
            @empty
                <!-- Empty notificaiton -->
                <div class="flex flex-col items-center justify-center p-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-4">
                        <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No notifications</h3>
                    <p class="text-center text-gray-500">You're all caught up! Check back later for new updates.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>