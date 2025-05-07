<section>
    <div wire:poll.visible="loadNotifications" class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-semibold text-gray-800">Notification</h1>
            </div>
            <div class="flex space-x-2">
                <flux:button wire:click="reloadNotifications" icon="arrow-path"
                    class="p-2 rounded-md border border-gray-200" />
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
        <div>
            @foreach ($groupedNotifications as $dateLabel => $items)
                @if (count($items) > 0)
                    <h2 class=" text-gray-500 font-medium">{{ $dateLabel }}</h2>

                    @foreach ($items as $notification)
                        <div wire:click="goDetailPage('{{ $notification->id }}')" class="mb-3 cursor-pointer ">
                            <div class="flex px-4 py-3 hover:bg-zinc-100 bg-zinc-50 rounded-lg">
                                <div class="mr-3">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100">
                                        <flux:icon.envelope class="size-5"/>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <flux:heading>{{ $notification->data['message'] ?? '-' }}</flux:heading>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <span>{{ $notification->data['letter_category'] ?? '-' }} -
                                            {{ $notification->created_at->format('H:i') }} -
                                            {{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            @endforeach
        </div>
    </div>
</section>