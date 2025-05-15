<div class="bg-white border-b border-gray-200 px-4 py-6">
    <div class="max-w-screen-xl mx-auto">
        <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">History Permohonan</h2>
                <p class="mt-1 text-sm text-gray-500">
                    View and manage all your application requests in one place
                </p>
            </div>
            <div class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                <!-- Filter Status
                <div class="relative w-full sm:w-auto">
                    <select
                        wire:model="filterStatus"
                        class="w-full appearance-none pr-8 pl-3 py-2 border rounded-md bg-white text-sm">
                        <option value="all">By Status</option>
                        <option value="pending">Pending</option>
                        <option value="process">Process</option>
                        <option value="replied">Replied</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 pr-2 flex items-center">
                        <x-lucide-chevron-down class="w-4 h-4 text-gray-400" />
                    </div>
                </div>

                <-- Sort Order -->
                <!-- <div class="relative w-full sm:w-auto">
                    <select
                        wire:model="sortOrder"
                        class="w-full appearance-none pr-8 pl-3 py-2 border rounded-md bg-white text-sm">
                        <option value="newest">Sort by: Newest</option>
                        <option value="oldest">Sort by: Oldest</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 pr-2 flex items-center">
                        <x-lucide-chevron-down class="w-4 h-4 text-gray-400" />
                    </div>
                </div>  -->

                <!-- Search -->
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-lucide-search class="w-5 h-5 text-gray-400" />
                    </div>
                    <flux:input
                        wire:model.live.debounce.500ms="searchQuery"
                        placeholder="Search requests..."
                        class="w-full sm:w-64" />
                </div>
            </div>
        </div>
    </div>
</div>
</div>