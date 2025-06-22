<div>
    <div class="lg:p-3">
        <flux:heading size="xl" level="1">{{ __('Daftar') }}</flux:heading>
        <flux:heading size="lg" level="2" class="mb-6">{{ __('Permohonan Layanan Sistem Informasi & Data') }}</flux:heading>

        <!-- Flash Messages -->
        @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
        @endif

        @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
        @endif

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
                        <th class="px-4 py-2 text-left border-b border-gray-200">#</th>
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
                            <span class="hidden status-text">{{ $item->status }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $item->kasatpelName($item->current_division) }}</td>
                        <td class="px-4 py-3">{{ $item->createdAtDMY() }}</td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Confirmation Modal -->
        <div x-data="{ 
        showModal: false,
        deleteCount: 0,
        init() {
            this.$wire.on('confirm-delete', (data) => {
                this.deleteCount = data[0].count;
                this.showModal = true;
            });
        }
    }"
            x-show="showModal"
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
            @keydown.escape.window="showModal = false">

            <!-- Backdrop -->
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                    @click="showModal = false"></div>

                <!-- Modal Content -->
                <div x-show="showModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">

                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-10 h-10 mx-auto bg-red-100 rounded-full sm:mx-0">
                            <svg class="w-6 h-6 text-red-600 mt-2 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Konfirmasi Hapus Data</h3>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm text-gray-500">
                            Apakah Anda yakin ingin menghapus <span class="font-semibold text-red-600" x-text="deleteCount"></span> data yang dipilih?
                            Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>

                    <div class="flex gap-3 justify-end">
                        <button @click="showModal = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Batal
                        </button>
                        <button wire:click="deleteSelected"
                            @click="showModal = false"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @once
    @push('styles')
    <!-- DataTables v2.3.2 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.min.css" />
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Custom CSS -->
    <style>
        /* Custom hover styling */
        .judul {
            max-width: 250px;
        }

        #requestsTable tbody tr:hover {
            background-color: #f9fafb !important;
        }

        #requestsTable tbody tr {
            transition: background-color 0.2s ease-in-out;
            cursor: pointer;
        }

        /* Don't apply cursor pointer to checkbox column */
        #requestsTable tbody tr td:first-child {
            cursor: default;
        }

        /* Filter styling */
        .dt-container .dt-search {
            display: none;
        }

        .dt-container .dt-length {
            margin-bottom: 1rem;
        }

        /* Hide status text for DataTables processing */
        .status-text {
            display: none !important;
        }

        /* Header filter dropdown styling */
        #statusFilterDropdown {
            right: 0;
            left: auto;
            min-width: 250px;
            max-width: 300px;
        }

        /* Ensure dropdown appears above table content */
        #statusFilterDropdown {
            z-index: 1000;
        }

        /* Multi-select dropdown styling */
        .status-checkbox-item {
            padding: 8px 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.15s ease;
        }

        .status-checkbox-item:hover {
            background-color: #f3f4f6;
        }

        .status-checkbox-item input[type="checkbox"] {
            margin: 0;
            cursor: pointer;
        }

        .status-checkbox-item label {
            cursor: pointer;
            flex: 1;
            font-size: 14px;
            color: #374151;
        }

        /* Selected count badge */
        .status-count-badge {
            background-color: #3b82f6;
            color: white;
            border-radius: 12px;
            padding: 2px 8px;
            font-size: 12px;
            margin-left: 8px;
            font-weight: 500;
        }

        /* Icon button styling */
        #statusFilterToggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Active filter indicator */
        .filter-active {
            background-color: #dbeafe !important;
            color: #3b82f6 !important;
        }

        /* Checkbox styling */
        .row-checkbox {
            transform: scale(1.1);
        }

        /* Hide element with x-cloak until Alpine.js loads */
        [x-cloak] {
            display: none !important;
        }

        /* Prevent header click conflicts */
        .status-header-content {
            pointer-events: none;
        }

        .status-header-content button {
            pointer-events: all;
        }
    </style>
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

    <script>
        let dataTable;
        let selectedStatuses = [];

        function initDataTable() {
            // Check if dependencies are loaded
            if (typeof $ === 'undefined' || typeof DataTable === 'undefined') {
                setTimeout(initDataTable, 100);
                return;
            }

            // Destroy existing table
            if (DataTable.isDataTable('#requestsTable')) {
                new DataTable('#requestsTable').destroy();
            }

            // Initialize DataTable v2
            dataTable = new DataTable('#requestsTable', {
                layout: {
                    topEnd: {
                        search: {
                            placeholder: 'Search...'
                        }
                    }
                },
                pageLength: 10,
                responsive: true,
                ordering: true,
                searching: true,
                columnDefs: [{
                        targets: 0, // Checkbox column
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        targets: 3, // Judul column
                        className: 'judul'
                    },
                    {
                        targets: 4, // Status column - disable sorting to prevent conflicts
                        orderable: false
                    }
                ],
                drawCallback: function() {
                    $('#requestsTable tbody tr').addClass('hover:bg-gray-50');

                    // Re-bind checkbox events after table redraw
                    bindCheckboxEvents();
                }
            });

            // Global search functionality
            $('#globalSearch').on('keyup', function() {
                dataTable.search(this.value).draw();
            });

            // Initialize status filter in header
            initHeaderStatusFilter();

            // Initial checkbox binding
            bindCheckboxEvents();
        }

        function bindCheckboxEvents() {
            // Handle row clicks (exclude checkbox column and status column)
            $('#requestsTable tbody tr').off('click').on('click', function(e) {
                // Don't navigate if clicking on checkbox column, status column, or action column
                const clickedColumnIndex = $(e.target).closest('td').index();
                if (clickedColumnIndex === 0 || clickedColumnIndex === 4 || clickedColumnIndex === 7) {
                    return;
                }

                const id = $(this).data('id');
                if (id) {
                    window.location.href = "{{ url('information-system') }}" + '/' + id;
                }
            });
        }

        const defaultStatusOptions = [{
                value: 'Permohonan Masuk',
                label: 'Permohonan Masuk'
            },
            {
                value: 'Didisposisikan',
                label: 'Didisposisikan'
            },
            {
                value: 'Revisi Kasatpel',
                label: 'Revisi Kasatpel'
            },
            {
                value: 'Revisi Kapusdatin',
                label: 'Revisi Kapusdatin'
            },
            {
                value: 'Disetujui Kasatpel',
                label: 'Disetujui Kasatpel'
            },
            {
                value: 'Disetujui Kapusdatin',
                label: 'Disetujui Kapusdatin'
            },
            {
                value: 'Proses Permohonan',
                label: 'Proses Permohonan'
            },
            {
                value: 'Permohonan Selesai',
                label: 'Permohonan Selesai'
            },
            {
                value: 'Ditolak',
                label: 'Ditolak'
            }
        ];

        function initHeaderStatusFilter() {
            // Populate checkbox options
            const container = $('#statusCheckboxContainer');
            container.empty();

            defaultStatusOptions.forEach((opt, idx) => {
                container.append(`
            <div class="status-checkbox-item">
                <input type="checkbox" id="status_${idx}" class="status-checkbox" value="${opt.value}">
                <label for="status_${idx}">${opt.label}</label>
            </div>
        `);
            });

            // Add custom search function for status filtering
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                // If no status filter selected, show all rows
                if (selectedStatuses.length === 0) {
                    return true;
                }

                // Get the status from the row data (assuming status is in column index 4)
                const row = settings.aoData[dataIndex].nTr;
                const rowStatus = $(row).data('status');

                // Return true if the row status matches any of the selected statuses
                return selectedStatuses.includes(rowStatus);
            });

            // Toggle dropdown visibility when clicking the filter icon
            $(document).on('click', '#statusFilterToggle', function(e) {
                e.stopPropagation();
                e.preventDefault();

                const dropdown = $('#statusFilterDropdown');
                const isVisible = !dropdown.hasClass('hidden');

                // Close all other dropdowns first
                $('.dropdown-menu').addClass('hidden');

                if (isVisible) {
                    dropdown.addClass('hidden');
                } else {
                    dropdown.removeClass('hidden');

                    // Position the dropdown correctly
                    positionDropdown();
                }
            });

            // Close dropdown when clicking the close button
            $(document).on('click', '#closeStatusFilter', function(e) {
                e.stopPropagation();
                $('#statusFilterDropdown').addClass('hidden');
            });

            // Close dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#statusFilterToggle, #statusFilterDropdown').length) {
                    $('#statusFilterDropdown').addClass('hidden');
                }
            });

            // Prevent dropdown from closing when clicking inside it
            $(document).on('click', '#statusFilterDropdown', function(e) {
                e.stopPropagation();
            });

            // Handle checkbox changes
            $(document).on('change', '.status-checkbox', function() {
                updateSelectedStatuses();
                applyStatusFilter();
            });

            // Select All functionality
            $(document).on('click', '#selectAllStatus', function(e) {
                e.preventDefault();
                $('.status-checkbox').prop('checked', true);
                updateSelectedStatuses();
                applyStatusFilter();
            });

            // Clear All functionality
            $(document).on('click', '#clearAllStatus', function(e) {
                e.preventDefault();
                $('.status-checkbox').prop('checked', false);
                updateSelectedStatuses();
                applyStatusFilter();
            });
        }

        function positionDropdown() {
            const toggle = $('#statusFilterToggle');
            const dropdown = $('#statusFilterDropdown');

            if (toggle.length && dropdown.length) {
                const toggleOffset = toggle.offset();
                const toggleHeight = toggle.outerHeight();
                const toggleWidth = toggle.outerWidth();

                // Position dropdown relative to the toggle button
                dropdown.css({
                    position: 'absolute',
                    top: '100%',
                    right: '0',
                    left: 'auto'
                });
            }
        }

        function updateSelectedStatuses() {
            selectedStatuses = [];
            $('.status-checkbox:checked').each(function() {
                selectedStatuses.push($(this).val());
            });

            updateFilterButtonVisual();
        }

        function updateFilterButtonVisual() {
            const filterButton = $('#statusFilterToggle');
            const count = selectedStatuses.length;

            if (count > 0) {
                filterButton.addClass('filter-active');
                // Optionally add a badge or indicator
                if (!filterButton.find('.filter-indicator').length) {
                    filterButton.append('<span class="filter-indicator absolute -top-1 -right-1 bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">' + count + '</span>');
                } else {
                    filterButton.find('.filter-indicator').text(count);
                }
            } else {
                filterButton.removeClass('filter-active');
                filterButton.find('.filter-indicator').remove();
            }
        }

        function applyStatusFilter() {
            // Simply redraw the table - the custom search function will handle the filtering
            dataTable.draw();
        }

        // Initialize on various events
        document.addEventListener('DOMContentLoaded', initDataTable);
        document.addEventListener('livewire:navigated', initDataTable);
        document.addEventListener('livewire:load', initDataTable);

        // jQuery ready fallback
        if (typeof $ !== 'undefined') {
            $(document).ready(initDataTable);
        }
    </script>
    @endpush
    @endonce