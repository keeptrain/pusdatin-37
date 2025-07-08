<div class="lg:p-3">
    <flux:heading size="xl" level="1">{{ __('Daftar') }}</flux:heading>
    <flux:heading size="lg" level="2" class="mb-6">{{ __('Permohonan Layanan Kehumasan') }}</flux:heading>

    <div class="flex flex-1 justify-between items-center mb-4 h-10">
        <!-- Left Side: Actions and Sort -->
        <div class="flex items-center space-x-2">

            @if (count($selectedPrRequest) > 0)
                <!-- Action Dropdown -->
                <flux:dropdown class="mr-2">
                    <flux:button size="sm" icon="ellipsis-vertical">
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
                <flux:button size="sm" variant="outline" icon:trailing="chevron-down">
                    Sort by
                </flux:button>

                <flux:menu>
                    <flux:menu.radio.group wire:model.live.debounce.1000ms="sortBy">
                        <flux:menu.radio value="latest_activity">Aktivitas terakhir</flux:menu.radio>
                        <flux:menu.radio value="date_created">Tanggal permohonan</flux:menu.radio>
                    </flux:menu.radio.group>
                </flux:menu>

            </flux:dropdown>

            <flux:dropdown>
                <flux:button size="sm" icon="adjustments-horizontal">Status</flux:button>

                <flux:menu>
                    <flux:menu.submenu heading="Tampilkan">
                        <flux:checkbox.group wire:model.live.debounce.1000ms="allowedStatuses" class="p-2">
                            @foreach ($statuses as $key => $status)
                                <flux:checkbox label="{{ $status }}" value="{{ $key }}" />
                            @endforeach
                        </flux:checkbox.group>
                    </flux:menu.submenu>
                    <flux:menu.separator />
                    <flux:menu.submenu heading="Filter">
                        <flux:menu.radio.group wire:model.live.debounce.1000ms="filterStatus">
                            @foreach ($statuses as $key => $status)
                                <flux:menu.radio wire:click="$set('filterStatus', '{{ $key }}')" value="{{ $key }}">
                                    {{ $status }}
                                </flux:menu.radio>
                            @endforeach
                        </flux:menu.radio.group>
                    </flux:menu.submenu>

                </flux:menu>
            </flux:dropdown>
        </div>
        <!-- Right Side: Search -->
        <div class="flex">
            <flux:input wire:model.live.debounce.500ms="searchQuery" icon="magnifying-glass" placeholder="Search..." />
        </div>
    </div>

    <flux:table.base :perPage="$perPage" :paginate="$this->publicRelations"
        emptyMessage="Belum ada data permohonan kehumasan">
        <x-slot name="header">
            <flux:table.column class="w-1 border-l-2 border-white dark:border-l-zinc-800">
                {{--
                <flux:checkbox wire:click="toggleSelectAll" /> --}}
            </flux:table.column>
            <flux:table.column class="flex items-center">Tanggal Selesai
                <flux:icon name="chevrons-up-down" class="size-3 ml-2 cursor-pointer"
                    wire:click="$set('sortBy', 'completed_date')" />
            </flux:table.column>
            <flux:table.column>Tema</flux:table.column>
            <flux:table.column>Penanggung Jawab</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            {{-- <flux:table.column>Bulan usulan</flux:table.column> --}}
            <flux:table.column>Bulan publikasi</flux:table.column>
            <flux:table.column>Rencana publikasi</flux:table.column>
            <flux:table.column></flux:table.column>

        </x-slot>

        <x-slot name="body">
            @foreach ($this->publicRelations as $item)
                <tr @click="$wire.show({{ $item->id }})"
                    class="{{ in_array($item->id, $selectedPrRequest) ? 'relative bg-zinc-50 dark:bg-zinc-900 ' : 'dark:bg-zinc-800' }}
                            border-b border-b-zinc-100 dark:border-b-zinc-800 hover:bg-gray-100 dark:hover:bg-zinc-900 cursor-pointer">
                    <flux:table.row class="{{ in_array($item->id, $selectedPrRequest) }}">
                        <div @click.stop>
                            <flux:checkbox wire:model.live="selectedPrRequest" value="{{ $item->id }}" />
                        </div>
                    </flux:table.row>
                    <flux:table.row>{{ $item->completed_date }}</flux:table.row>
                    <flux:table.row>{{ $item->theme }}</flux:table.row>
                    <flux:table.row>{{ $item->user->name }}</flux:table.row>
                    <flux:table.row>
                        <flux:notification.status-badge :status="$item->status" />
                    </flux:table.row>
                    {{-- <flux:table.row>{{ $item->proposedMonth() }}</flux:table.row> --}}
                    <flux:table.row>{{ $item->month_publication }}</flux:table.row>
                    <flux:table.row>{{ $item->spesificDate() }}</flux:table.row>
                    <flux:table.row>
                        <div @click.stop>
                            <flux:dropdown>

                                <flux:button icon:trailing="ellipsis-vertical" variant="ghost"></flux:button>

                                <flux:menu>
                                    {{-- <flux:menu.item :href="route('letter.activity', [$item->id])" icon="list-bullet"
                                        wire:navigate>Activity
                                    </flux:menu.item> --}}
                                    <flux:menu.item :href="route('pr.rollback', [$item->id])" icon="pencil-square"
                                        wire:navigate>Edit</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </div>
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
            <td class="py-3">&nbsp;</td>
        </x-slot>
    </flux:table.base>

    <x-modal.delete-selected />
</div>