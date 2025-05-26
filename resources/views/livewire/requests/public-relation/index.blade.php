<div class="lg:p-3">

    @can('view pr request', $this->publicRelations)
        <flux:heading size="xl" level="1">{{ __('List') }}</flux:heading>
        <flux:heading size="lg" level="2" class="mb-6">{{ __('Permohonan Layanan Kehumasan') }}</flux:heading>

        <flux:menu.tabs :statuses="$statuses" :filterStatus="$filterStatus" />

        <div class="flex flex-1 justify-between items-center mb-4 h-10">
            <!-- Left Side: Actions and Sort -->
            <div class="flex items-center space-x-2">
                @if (count($selectedPrRequest) > 0)
                    <!-- Action Dropdown -->
                    <flux:dropdown class="mr-2">
                        <flux:button class="bg-zinc-100 dark:bg-zinc-900" icon="ellipsis-vertical">
                            <span class="hidden lg:inline">Actions</span>
                        </flux:button>

                        <flux:menu>
                            <flux:modal.trigger name="confirm-deletion">
                                <flux:menu.item variant="danger" icon="trash" x-data=""
                                    x-on:click.prevent="$dispatch('open-modal', 'confirm-letter-deletion')">Delete
                                    ({{ count($selectedPrRequest) }})</flux:menu.item>
                            </flux:modal.trigger>
                        </flux:menu>
                    </flux:dropdown>
                @endif

                <!-- Sort Dropdown -->
                <flux:dropdown>
                    <flux:button class="bg-zinc-100 dark:bg-zinc-900" icon:trailing="chevron-down">
                        Sort by
                    </flux:button>

                    <flux:menu>
                        <flux:menu.radio.group wire:model.live.debounce.1000ms="sortBy">
                            <flux:menu.radio value="latest_activity">Latest activity</flux:menu.radio>
                            <flux:menu.radio value="date_created">Date created</flux:menu.radio>
                        </flux:menu.radio.group>
                    </flux:menu>
                </flux:dropdown>
            </div>
            <!-- Right Side: Search -->
            <div class="flex">
                <flux:input wire:model.live.debounce.500ms="searchQuery" icon="magnifying-glass" placeholder="Search..." />
            </div>
        </div>

        <flux:table.base :perPage="$perPage" :paginate="$this->publicRelations" emptyMessage="No data letter available.">
            <x-slot name="header">
                <flux:table.column class="w-1 border-l-2 border-white dark:border-l-zinc-800">
                    {{--
                    <flux:checkbox wire:click="toggleSelectAll" /> --}}
                </flux:table.column>
                <flux:table.column>Bulan usulan</flux:table.column>
                <flux:table.column>Tema Pesan Kesehatan</flux:table.column>
                <flux:table.column>Penanggung Jawab</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Bulan publikasi</flux:table.column>
                <flux:table.column>Rencana publikasi</flux:table.column>
                <flux:table.column></flux:table.column>
            </x-slot>

            <x-slot name="body">
                @foreach ($this->publicRelations as $item)
                    <tr @click="$wire.show({{ $item->id }})"
                        class="{{ in_array($item->id, $selectedPrRequest) ? 'relative bg-zinc-50 dark:bg-zinc-900 ' : 'dark:bg-zinc-800' }}
                            border-b border-b-zinc-100 dark:border-b-zinc-800 hover:bg-gray-100 dark:hover:bg-zinc-900 cursor-pointer">
                        <flux:table.row @click.stop class="{{ in_array($item->id, $selectedPrRequest) }}">
                            <flux:checkbox wire:model.live="selectedPrRequest" value="{{ $item->id }}" />
                        </flux:table.row>
                        <flux:table.row>{{ $item->proposedMonth() }}</flux:table.row>
                        <flux:table.row>{{ $item->theme }}</flux:table.row>
                        <flux:table.row>{{ $item->user->name }}</flux:table.row>
                        <flux:table.row>
                            <flux:notification.status-badge :status="$item->status" />
                        </flux:table.row>
                        <flux:table.row>{{ $item->month_publication }}</flux:table.row>
                        <flux:table.row>{{ $item->spesificDate() }}</flux:table.row>
                        <flux:table.row>
                            <flux:dropdown @click.stop>
                                <flux:button icon:trailing="ellipsis-vertical" variant="ghost"></flux:button>

                                <flux:menu>
                                    {{-- <flux:menu.item :href="route('letter.activity', [$item->id])" icon="list-bullet"
                                        wire:navigate>Activity
                                    </flux:menu.item> --}}
                                    <flux:menu.item :href="route('letter.chat', [$item->id])" icon="chat-bubble-left-right"
                                        wire:navigate>Chat</flux:menu.item>
                                    <flux:menu.item :href="route('letter.rollback', [$item->id])" icon="backward" wire:navigate>
                                        Rollback</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.row>
                    </tr>
                @endforeach
            </x-slot>

            <x-slot name="emptyRow">
                <td class="py-3">&nbsp;</td>
                <td class="py-3">&nbsp;</td>
                <td class="py-3">&nbsp;</td>
                <td class="py-3">&nbsp;</td>
                <td class="py-3">&nbsp;</td>
                <td class="py-3">&nbsp;</td>
                <td class="py-3">&nbsp;</td>
                <td class="py-3">&nbsp;</td>
            </x-slot>
        </flux:table.base>

        <x-modal.delete-selected />
    @endcan
</div>