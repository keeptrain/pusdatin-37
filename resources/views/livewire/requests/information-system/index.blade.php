<div x-data="requestsTable" class="lg:p-3">
    <flux:heading size="xl" level="1">{{ __('Daftar') }}</flux:heading>
    <flux:heading size="lg" level="2" class="mb-6">{{ __('Permohonan Layanan Sistem Informasi & Data') }}
    </flux:heading>
    <x-flash-messages />

    <div class="flex justify-between items-center mb-4">
        <flux:button x-on:click="$dispatch('modal-show', { name: 'confirm-deletion' }); sendSelectedId();" icon="trash"
            x-bind:disabled="selectedId.length === 0">Hapus data <span x-text="`(${selectedId.length})`"></span>
        </flux:button>

        <div class="flex-shrink-0">
            <div class="flex-1">
                <input type="text" id="globalSearch" placeholder="Search..."
                    class="px-3 py-2 border border-gray-300 rounded shadow-sm w-full max-w-md" />
            </div>
        </div>
    </div>

    <!-- DataTables Table dengan wire:ignore -->
    <div wire:ignore>
        <table id="requestsTable" class="border border-zinc-200 stripe" style="width: 100%;">
            <thead id="requestsTableHeader" class="bg-gray-50 text-sm uppercase">
                <tr>
                    <th class="table-header">
                    </th>
                    <th class="table-header">Penanggung Jawab</th>
                    <th class="table-header">Judul</th>
                    <th wire:key="{{ rand() }}" class="table-header relative">
                        <div class="flex items-center justify-between">
                            <span>Status</span>
                            <!-- Badge showing number of selected filters -->
                            <span
                                class="absolute -top-1 -right-1 bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                <span x-text="selectedStatuses.length"></span>
                            </span>
                            <button type="button" id="statusFilterToggle"
                                class="ml-2 p-1 hover:bg-gray-200 rounded transition-colors">
                                <flux:icon.adjustments-vertical class="size-5 text-gray-600 hover:text-gray-800" />
                            </button>
                        </div>
                        <x-layouts.table.filter-status />
                    </th>
                    <th class="table-header">Kasatpel</th>
                    <th class="table-header">Diajukan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($systemRequests as $idx => $item)
                    <tr wire:key="{{ $item->id }}" x-on:click="window.location.href = '{{ route('is.show', $item->id) }}'"
                        class="border-b">
                        <td @click.stop class="px-4 py-3">
                            <input type="checkbox" value="{{ $item->id }}" x-model="selectedId">
                        </td>
                        <td class="px-4 py-3">{{ $item->user->name }}
                        </td>
                        <td class="px-4 py-3">{{ $item->title }}</td>
                        <td class="px-4 py-3">
                            <flux:notification.status-badge :status="$item->status" />
                        </td>
                        <td class="px-4 py-3">{{ $item->kasatpelName($item->current_division) }}</td>
                        <td class="px-4 py-3">{{ $item->createdAtDMY() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-modal.delete-selected />

    @assets
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables v2.3.2 -->
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
    <!-- DataTables v2.3.2 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/information-system-index.css') }}" />
    @endassets

    <!-- Custom JS -->
    @script
    @include('livewire.requests.information-system.blade-script.index-script')
    @endscript

</div>