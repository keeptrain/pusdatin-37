<!-- Status Filter Dropdown -->
<div id="statusFilterDropdown"
    class="absolute top-full left-0 mt-1 bg-white border border-gray-300 rounded shadow-lg z-50 hidden max-h-64 overflow-y-auto min-w-64">
    <!-- Filter Status Label -->
    <div class="px-3 py-2 border-b border-gray-200 bg-gray-50">
        <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-gray-700" id="statusFilterText">Filter
                Status</span>
            <button type="button" id="closeStatusFilter" class="text-gray-400 hover:text-gray-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Select All / Clear All -->
    <div class="px-3 py-2 border-b border-gray-200 bg-gray-50">
        <div class="flex gap-2">
            <button type="button" id="selectAllStatus" class="text-xs text-blue-600 hover:text-blue-800">Select
                All</button>
            <span class="text-gray-400">|</span>
            <button type="button" id="clearAllStatus" class="text-xs text-gray-600 hover:text-gray-800">Clear
                All</button>
        </div>
    </div>

    <!-- Status Options -->
    <div id="statusCheckboxContainer" class="py-1">
        <!-- Container untuk status checkboxes -->
    </div>
</div>
{{-- <div class="flex items-center justify-between">
    <span>Status</span>
    <button type="button" x-on:click="toggleStatusFilter()" id="statusFilterToggle"
        class="ml-2 p-1 hover:bg-gray-200 rounded transition-colors">
        <flux:icon.adjustments-vertical class="size-5 text-gray-600 hover:text-gray-800" />
        <!-- Badge showing number of selected filters -->
        <span x-show="selectedStatuses.length > 0"
            class="absolute -top-1 -right-1 bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
            <span x-text="selectedStatuses.length"></span>
        </span>
    </button>
</div>

<!-- Status Filter Dropdown -->
<div id="statusFilterDropdown" x-show="isFilterOpen" x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95" @click.away="isFilterOpen = false"
    class="absolute top-full left-0 mt-1 bg-white border border-gray-300 rounded shadow-lg z-50 max-h-64 overflow-y-auto min-w-64">
    <!-- Filter Status Label -->
    <div class="px-3 py-2 border-b border-gray-200 bg-gray-50">
        <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-gray-700">Filter Status</span>
            <button type="button" x-on:click="isFilterOpen = false" class="text-gray-400 hover:text-gray-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Select All / Clear All -->
    <div class="px-3 py-2 border-b border-gray-200 bg-gray-50">
        <div class="flex gap-2">
            <button type="button" x-on:click="selectAllStatuses()"
                class="text-xs text-blue-600 hover:text-blue-800">Select
                All</button>
            <span class="text-gray-400">|</span>
            <button type="button" x-on:click="clearAllStatuses()"
                class="text-xs text-gray-600 hover:text-gray-800">Clear
                All</button>
        </div>
    </div>

    <!-- Status Options -->
    <div class="py-1">
        <template x-for="(option, index) in statusOptions" :key="index">
            <div class="px-3 py-1 hover:bg-gray-50">
                <div class="flex items-center">
                    <input type="checkbox" :id="'status_' + index" x-model="selectedStatuses" :value="option . value"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        :checked="selectedStatuses.includes(option.value)">
                    <label :for="'status_' + index" class="ml-2 text-sm text-gray-700" x-text="option.label"></label>
                </div>
            </div>
        </template>
    </div>
</div> --}}