<div class="lg:p-3">

    <flux:heading size="xl" level="1" class="mb-6">{{ __('List Service Request') }}</flux:heading>
    <flux:menu.tabs :statuses="$statuses" :filterStatus="$filterStatus" />

    <div class="flex flex-1 justify-between items-center mb-4 h-10">
        <!-- Left Side: Actions and Sort -->
        <div class="flex items-center space-x-2">
            @if (count($selectedLetters) > 0)
                <!-- Action Dropdown -->
                <flux:dropdown class="mr-2">
                    <flux:button class="bg-zinc-100 dark:bg-zinc-900" icon="ellipsis-vertical">
                        <span class="hidden lg:inline">Actions</span>
                    </flux:button>

                    <flux:menu>
                        <flux:modal.trigger name="confirm-letter-deletion">
                            <flux:menu.item variant="danger" icon="trash" x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'confirm-letter-deletion')">Delete
                                ({{ count($selectedLetters) }})</flux:menu.item>
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
            {{-- for testing --}}
            <flux:button icon="plus-circle" :href="route('letter.upload')" class="p-2 mr-2"></flux:button>
            <flux:input wire:model.live.debounce.500ms="searchQuery" icon="magnifying-glass" placeholder="Search..." />
        </div>
    </div>

    <flux:table.base :perPage="$perPage" :paginate="$this->publicRelations" emptyMessage="No data letter available.">
        <x-slot name="header">
            <flux:table.column class="w-1 border-l-2 border-white dark:border-l-zinc-800">
                <flux:checkbox wire:click="toggleSelectAll" />
            </flux:table.column>
            <flux:table.column>Bulan usulan</flux:table.column>
            <flux:table.column>Tema Pesan Kesehatan</flux:table.column>
            <flux:table.column>Seksi Pengusul</flux:table.column>
            <flux:table.column>Penanggung Jawab</flux:table.column>
            <flux:table.column>Rencana Publikasi</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column></flux:table.column>
        </x-slot>

        <x-slot name="body">
            @foreach ($this->publicRelations as $item)
                    <tr 
                        @click = "$wire.show({{ $item->id }})"
                        class="{{ in_array($item->id, $selectedLetters) ? 'relative bg-zinc-50 dark:bg-zinc-900 ' : 'dark:bg-zinc-800' }}
                        border-b border-b-zinc-100 dark:border-b-zinc-800 hover:bg-gray-100 dark:hover:bg-zinc-900 cursor-pointer">
                        <flux:table.row @click.stop class="{{ in_array($item->id, $selectedLetters)
                ? '  border-s-2 border-black dark:border-white'
                : 'border-l-2 border-white dark:border-l-zinc-800' }} ">
                            <flux:checkbox wire:model.live="selectedLetters" value="{{ $item->id }}" />
                        </flux:table.row>
                        <flux:table.row>{{ $item->month_publication }}</flux:table.row>
                        <flux:table.row>{{ $item->theme }}</flux:table.row>
                        <flux:table.row>{{ $item->section }}</flux:table.row>
                        <flux:table.row>{{ $item->responsible_person }}</flux:table.row>
                        <flux:table.row>{{ $item->month_publication }}</flux:table.row>
                        <flux:table.row>
                            <flux:notification.status-badge :status="$item->status->label()">
                                {{ $item->status->label() }}</flux:notification.status-badge>
                        </flux:table.row>

                        <flux:table.row>
                            <flux:dropdown @click.stop>
                                <flux:button icon:trailing="ellipsis-vertical" variant="ghost"></flux:button>

                                <flux:menu>
                                    <flux:menu.item :href="route('letter.activity', [$item->id])" icon="list-bullet"
                                        wire:navigate>Activity
                                    </flux:menu.item>
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

    <flux:modal name="confirm-letter-deletion" focusable class="max-w-lg">
        <form wire:submit="deleteSelected" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Delete selected letters') }}</flux:heading>

                <flux:subheading>
                    {{ __('Are you sure you would like to do this?') }}
                </flux:subheading>
            </div>
            <div class="flex justify-end space-x-2">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" type="submit">{{ __('Confirm') }}</flux:button>
            </div>
        </form>
    </flux:modal>


</div>