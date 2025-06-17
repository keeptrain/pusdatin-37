<div class="space-y-6">
@if (!$dashboardUser)
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-xl font-semibold text-gray-800">Notifikasi</h1>
        </div>
        <div class="flex space-x-2">
            <flux:modal.close>
                <flux:button icon="x-mark" />
            </flux:modal.close>
        </div>
    </div>

    <div x-data="{ activeTab: 'all', tabBasedRole: {{ json_encode($userTabs) }} }" class="w-full max-w-3xl mx-auto">
        {{-- Navigasi Tab --}}
        <div class="mb-4 border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
            <ul class="flex -mb-px text-sm font-medium text-center" id="notificationTabs" role="tablist">
                <template x-for="tab in tabBasedRole" :key="tab">
                    <li class="mr-2" role="presentation">
                        <button @click="activeTab = tab" :class="{
                                    'text-blue-600 border-blue-600 active dark:text-blue-500 dark:border-blue-500': activeTab === tab,
                                    'text-gray-500 hover:text-gray-600 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 border-transparent': activeTab !== tab
                                }" class="inline-block p-4 border-b-3 rounded-t-lg focus:outline-none"
                            x-text="tab.charAt(0).toUpperCase() + tab.slice(1)">
                        </button>
                    </li>
                </template>
            </ul>
        </div>

        <!-- Panel untuk "Semua" -->
        <div x-show="activeTab === 'all'" wire:poll.visible>
            @forelse ($this->notifications as $dateLabel => $statuses)
                <div class="px-4 py-2 bg-zinc-50 border-b border-gray-200 mt-4">
                    <h3 class="text-sm font-medium text-gray-500">{{ $dateLabel }}</h3>
                </div>
                @foreach ($statuses as $status => $items)
                    @foreach ($items as $item)
                        <x-notifications.notificaition-adapter :item="$item" />
                    @endforeach
                @endforeach
            @empty
                <div class="flex flex-col items-center justify-center p-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-4">
                        <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                            </path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada notifikasi</h3>
                    <p class="text-center text-gray-500">Anda sudah terupdate!</p>
                </div>
            @endforelse
        </div>

        @hasrole('head_verifier|si_verifier|data_verifier|pr_verifier')
        <!-- Panel untuk "Disposisi" -->
        <div x-show="activeTab === 'disposisi'">
            @foreach ($this->getFilteredDispositionNotifications as $dateLabel => $statuses)
                @foreach ($statuses as $status => $items)
                    <div class="px-4 py-2 bg-zinc-50 border-b border-gray-200 mt-4">
                        <h3 class="text-sm font-medium text-gray-500">{{ $dateLabel }}</h3>
                    </div>
                    @foreach ($items as $item)
                        <x-notifications.notificaition-adapter :item="$item" />
                    @endforeach
                @endforeach
            @endforeach
        </div>

        <!-- Panel untuk "Disetujui" -->
        <div x-show="activeTab === 'disetujui'">
            @foreach ($this->getFilteredApprovedNotifications as $dateLabel => $statuses)
                @foreach ($statuses as $status => $items)
                    <div class="px-4 py-2 bg-zinc-50 border-b border-gray-200 mt-4">
                        <h3 class="text-sm font-medium text-gray-500">{{ $dateLabel }}</h3>
                    </div>
                    @foreach ($items as $item)
                        <x-notifications.notificaition-adapter :item="$item" />
                    @endforeach
                @endforeach
            @endforeach
        </div>
        @endhasrole

        <!-- Panel untuk "Balasan" -->
        <div x-show="activeTab === 'revisi'">
            @foreach ($this->getFilteredRepliedNotifications as $dateLabel => $statuses)
                @foreach ($statuses as $status => $items)
                    <div class="px-4 py-2 bg-zinc-50 border-b border-gray-200 mt-4">
                        <h3 class="text-sm font-medium text-gray-500">{{ $dateLabel }}</h3>
                    </div>
                    @foreach ($items as $item)
                        <x-notifications.notificaition-adapter :item="$item" />
                    @endforeach
                @endforeach
            @endforeach
        </div>
    </div>
    @else
    <div>
    @forelse ($this->notifications as $dateLabel => $statuses)
        @foreach ($statuses as $status => $items)
            @foreach ($items as $item)
                <div wire:click="goDetailPage('{{ $item['id'] }}')" class="p-4 border-b mb-2 border-gray-100 transition-colors cursor-pointer relative hover:shadow-xs">
                    <div class="absolute left-2 top-5 w-2 h-2 bg-zinc-400 rounded-full"></div>
                    <div class="ml-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $item['message'] }}
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                </p>
                                <p class="text-xs text-gray-500 mt-2 flex items-center">
                                    <flux:icon.clock class="size-4 mr-1" />
                                    {{  $dateLabel }} - {{ $item['created_at'] }}
                                </p>
                            </div>
                            <div class="ml-2">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-zinc-100 text-zinc-800">
                                    {{ $item['status'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
    @empty
        <div class="flex flex-col items-center justify-center p-8">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-4">
                <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                    </path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
            </div>
            <flux:subheading class="text-gray-400">Belum ada notifikasi</flux:subheading>
        </div>
    @endforelse
    </div>
@endif
</div>
 

