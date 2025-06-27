<div>
    <div class="lg:p-3">
        <flux:heading size="xl" level="1">{{ __('Daftar') }}</flux:heading>
        <flux:heading size="lg" level="2" class="mb-6">{{ __('Permohonan Layanan Sistem Informasi & Data') }}</flux:heading>

        <!-- Flash Messages Component -->
        <x-flash-messages />

        <!-- Simplified Filters Section - Only Global Search -->
        <div class="mb-4">
            <div class="flex-1">
                <input
                    type="text"
                    id="globalSearch"
                    placeholder="Search..."
                    class="px-3 py-2 border border-gray-300 rounded shadow-sm w-full max-w-md" />
            </div>
        </div>

        <!-- Action Buttons Section -->
        <div class="flex gap-2 mb-4">
            <button
                wire:click="confirmDelete"
                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                :disabled="@js(empty($selectedRequests))"
                @disabled(empty($selectedRequests))>
                <i class="fas fa-trash mr-2"></i>
                Hapus Terpilih (<span x-text="@js(count($selectedRequests))">{{ count($selectedRequests) }}</span>)
            </button>

            @if(!empty($selectedRequests))
            <button
                wire:click="$set('selectedRequests', [])"
                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors">
                <i class="fas fa-times mr-2"></i>
                Batal Pilih
            </button>
            @endif
        </div>

        <!-- DataTables Table -->
        <div wire:ignore>
            <table id="requestsTable" class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left border-b border-gray-200 w-12">
                            <input
                                type="checkbox"
                                id="selectAllCheckbox"
                                wire:model.live="selectAll"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-4 py-2 text-left border-b border-gray-200">No</th>
                        <th class="px-4 py-2 text-left border-b border-gray-200">Penanggung Jawab</th>
                        <th class="px-4 py-2 text-left border-b border-gray-200 judul">Judul</th>
                        <th class="px-4 py-2 text-left border-b border-gray-200 relative">
                            <div class="flex items-center justify-between">
                                <span>Status</span>
                                <button type="button" id="statusFilterToggle" class="ml-2 p-1 hover:bg-gray-200 rounded transition-colors">
                                    <flux:icon.adjustments-vertical class="size-5 text-gray-600 hover:text-gray-800" />
                                </button>
                            </div>

                            <!-- Status Filter Dropdown -->
                            <div
                                id="statusFilterDropdown"
                                class="absolute top-full left-0 mt-1 bg-white border border-gray-300 rounded shadow-lg z-50 hidden max-h-64 overflow-y-auto min-w-64">
                                <!-- Filter Status Label -->
                                <div class="px-3 py-2 border-b border-gray-200 bg-gray-50">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-700" id="statusFilterText">Filter Status</span>
                                        <button type="button" id="closeStatusFilter" class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Select All / Clear All -->
                                <div class="px-3 py-2 border-b border-gray-200 bg-gray-50">
                                    <div class="flex gap-2">
                                        <button type="button" id="selectAllStatus" class="text-xs text-blue-600 hover:text-blue-800">Select All</button>
                                        <span class="text-gray-400">|</span>
                                        <button type="button" id="clearAllStatus" class="text-xs text-gray-600 hover:text-gray-800">Clear All</button>
                                    </div>
                                </div>

                                <!-- Status Options -->
                                <div id="statusCheckboxContainer" class="py-1">
                                    <!-- Checkboxes will be populated here -->
                                </div>
                            </div>
                        </th>
                        <th class="px-4 py-2 text-left border-b border-gray-200">Kasatpel</th>
                        <th class="px-4 py-2 text-left border-b border-gray-200">Tanggal Permohonan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $idx => $item)
                    <tr class="border-b border-gray-200 transition-colors duration-200 hover:bg-gray-50"
                        data-status="{{ $item->status->label() }}"
                        data-id="{{$item->id}}">
                        <td class="px-4 py-3">
                            <input
                                type="checkbox"
                                wire:model.live="selectedRequests"
                                value="{{ $item->id }}"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 row-checkbox"
                                onclick="event.stopPropagation()">
                        </td>
                        <td class="px-4 py-3">{{ $idx + 1 }}</td>
                        <td class="px-4 py-3">{{ $item->user->name }}</td>
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

        <!-- Confirmation Modal Component -->
        <x-delete-confirmation-modal />
    </div>

    @once
    @push('styles')
    <!-- DataTables v2.3.2 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/information-system-index.css') }}" />
    @endpush

    @push('scripts')
    <!-- DataTables v2.3.2 JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <!-- Custom JavaScript -->
    <script src="{{ asset('js/information-system-index.js') }}"></script>
    @endpush
    @endonce
</div>