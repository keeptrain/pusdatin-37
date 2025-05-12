<div class="bg-white border-b border-gray-200 px-4 py-6">
    <div class="max-w-screen-xl mx-auto">
        <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">History Permohonan</h2>
                <p class="mt-1 text-sm text-gray-500">View and manage all your application requests in one place</p>
            </div>
            <div class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                <!-- Filter Select -->
                <div class="relative w-full sm:w-auto">
                    <select class="w-full appearance-none pr-8 pl-3 py-2 border border-gray-300 rounded-md bg-white text-gray-700 text-sm focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500">
                        <option>All Requests</option>
                        <option>Pending</option>
                        <option>Approved</option>
                        <option>Rejected</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <x-lucide-chevron-down class="w-4 h-4 text-gray-400" />
                    </div>
                </div>
                <!-- Sort Select -->
                <div class="relative w-full sm:w-auto">
                    <select class="w-full appearance-none pr-8 pl-3 py-2 border border-gray-300 rounded-md bg-white text-gray-700 text-sm focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500">
                        <option>Sort by: Newest</option>
                        <option>Sort by: Oldest</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <x-lucide-chevron-down class="w-4 h-4 text-gray-400" />
                    </div>
                </div>
                <!-- Search Input -->
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-lucide-search class="w-5 h-5 text-gray-400" />
                    </div>
                    <input
                        type="text"
                        placeholder="Search requests..."
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md bg-white text-gray-700 text-sm focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500" />
                </div>
            </div>
        </div>
    </div>
</div>