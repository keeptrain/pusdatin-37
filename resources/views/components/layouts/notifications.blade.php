<div class="max-w-lg mx-auto">
    <!-- Header -->
    <div class="p-4 border-b flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Notification</h1>
        </div>
        <div class="flex space-x-2">
            <flux:button icon="arrow-path" class="p-2 rounded-md border border-gray-200" />

            <flux:button icon="x-mark" :closable="true" />
        </div>
    </div>

    <!-- Tab navigation -->
    <div class="flex border-b bg-zinc-50">
        {{-- <button class="flex items-center px-4 py-3 text-gray-800 font-medium border-b-2 border-blue-500">
            All <span class="ml-2 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">6</span>
        </button>
        <button class="flex items-center px-4 py-3 text-gray-500">
            Payments
        </button> --}}
        <div class="ml-auto flex items-center pr-4">
            <flux:button icon="check" variant="ghost" class="flex items-center text-blue-600">
                Mark all as read
            </flux:button>
        </div>
    </div>

    <!-- Notification list -->
    <div>
        <h2 class="px-4 py-2 text-gray-500 font-medium">Today</h2>
        
        <!-- Notification item 4 -->
        <div class="flex px-4 py-3 hover:bg-gray-50 border-b">
            <div class="mr-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <div class="font-medium">Barber Tom has updated his availability for the week.</div>
                <div class="flex items-center text-sm text-gray-500">
                    <span>Shift reminder - Feb 12, 2025</span>
                    <span class="mx-2">•</span>
                    <span>3:00 PM.</span>
                </div>
            </div>
        </div>

        <h2 class="px-4 py-2 text-gray-500 font-medium">Yesterdays</h2>

        <!-- Notification item 4 -->
        <div class="flex px-4 py-3 hover:bg-gray-50 border-b">
            <div class="mr-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <div class="font-medium">Barber Tom has updated his availability for the week.</div>
                <div class="flex items-center text-sm text-gray-500">
                    <span>Shift reminder - Feb 12, 2025</span>
                    <span class="mx-2">•</span>
                    <span>3:00 PM.</span>
                </div>
            </div>
        </div>
    </div>
</div>
