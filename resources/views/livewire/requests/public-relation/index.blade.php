<div x-data="prRequestsTable" class="lg:p-3">
    <flux:heading size="xl" level="1">{{ __('Daftar') }}</flux:heading>
    <flux:heading size="lg" level="2" class="mb-6">{{ __('Permohonan Layanan Kehumasan') }}</flux:heading>

    <!-- Flash Messages Component -->
    <x-flash-messages />

    <!-- Top Controls Section - Search Bar and Delete Button -->
    <div class="flex justify-between items-center mb-4">
        <flux:button x-on:click="$dispatch('modal-show', { name: 'confirm-deletion' }); sendSelectedId();" icon="trash"
            x-bind:disabled="selectedId.length === 0">Hapus data <span x-text="`(${selectedId.length})`"></span>
        </flux:button>

        <!-- Right Side - Search Bar -->
        <div class="flex-shrink-0">
            <input type="text" id="globalSearch" placeholder="Search..."
                class="px-3 py-2 border border-gray-300 rounded shadow-sm w-50" />
        </div>
    </div>

    <div wire:ignore>
        <!-- DataTables Table -->
        <table id="prRequestsTable" class="border border-zinc-200 display">
            <thead class="bg-gray-50 uppercase text-sm">
                <tr>
                    <th class="px-4 py-2 text-left border-b border-gray-200 w-12">
                    </th>
                    <th class="px-4 py-2 text-left border-b border-gray-200">Tanggal Selesai</th>
                    <th class="px-4 py-2 text-left border-b border-gray-200">Tema</th>
                    <th class="px-4 py-2 text-left border-b border-gray-200">Penanggung Jawab</th>
                    <th wire:key="{{ rand() }}" class="px-4 py-2 text-left border-b border-gray-200 relative">
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
                    <th class="px-4 py-2 text-left border-b border-gray-200">Bulan/Rencana Publikasi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($publicRelations as $item)
                    <tr wire:key="{{ $item->id }}" x-on:click="window.location.href = '{{ route('pr.show', $item->id) }}'"
                        class="border-b border-gray-200 transition-colors duration-200 hover:bg-gray-50">
                        <td @click.stop class="px-4 py-3">
                            <input type="checkbox" value="{{ $item->id }}" x-model="selectedId">
                        </td>
                        <td class="px-4 py-3">{{ $item->completed_date }}</td>
                        <td class="px-4 py-3">{{ $item->theme }}</td>
                        <td class="px-4 py-3">{{ $item->user->name }}</td>
                        <td class="px-4 py-3">
                            <flux:notification.status-badge :status="$item->status" />
                        </td>
                        <td class="px-4 py-3">{{ $item->month_publication }} / {{ $item->spesificDate() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Confirmation Modal Component -->
    <x-modal.delete-selected />

    @assets
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables v2.3.2 -->
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
    <!-- DataTables v2.3.2 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css" />
    <!-- Custom CSS -->
    {{--
    <link rel="stylesheet" href="{{ asset('css/public-relation-index.css') }}" /> --}}
    @endassets

    @script
    @include('livewire.requests.public-relation.blade-script.index-script')
    @endscript
</div>