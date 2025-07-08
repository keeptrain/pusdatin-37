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
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        @disabled($isDeleting)>
                </th>
                {{-- <th class="px-4 py-2 text-left border-b border-gray-200">No</th> --}}
                <th class="px-4 py-2 text-left border-b border-gray-200">Penanggung Jawab</th>
                <th class="px-4 py-2 text-left border-b border-gray-200 judul">Judul</th>
                <th class="px-4 py-2 text-left border-b border-gray-200 relative">
                    <div class="flex items-center justify-between">
                        <span>Status</span>
                        <button type="button" id="statusFilterToggle"
                            class="ml-2 p-1 hover:bg-gray-200 rounded transition-colors"
                            :disabled="@js($isDeleting)">
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
                        onclick="event.stopPropagation()"
                        @disabled($isDeleting)>
                </td>
                {{-- <td class="px-4 py-3">{{ $idx + 1 }}</td> --}}
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