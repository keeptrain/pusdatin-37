<!-- Notifications List (Right - 1 column) -->
<div class="mt-6 space-y-4 p-4">
    <!-- Notifications Header -->
    <div class="  border-gray-200 space-y-2">
        <div class="flex items-center justify-between">
            <flux:heading size="xl" level="1" class="text-blue-900">Notifikasi</flux:heading>
            {{-- <flux:button size="sm" variant="subtle">
                Tandai semua dibaca
            </flux:button> --}}
            {{-- <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                {{ $notifications->count() }} Baru
            </span> --}}
        </div>
    </div>
   
    <!-- Notifications List -->
    <div class="max-h-80 h-80 overflow-y-auto">
            
        <livewire:admin.notifications :dashboardUser="true" />
         {{-- <!-- Notification Item 3 - Unread -->
        <div class="p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer relative">
            <div class="absolute left-2 top-4 w-2 h-2 bg-yellow-500 rounded-full"></div>
            <div class="ml-4">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            Reminder Meeting
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            Meeting evaluasi sistem akan dimulai dalam 30 menit
                        </p>
                        <p class="text-xs text-gray-500 mt-2 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            30 menit yang lalu
                        </p>
                    </div>
                    <div class="ml-2">
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Reminder
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Item 4 - Read -->
        <div class="p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer">
            <div class="ml-4">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-700 truncate">
                            Update Sistem
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            Sistem informasi telah diperbarui ke versi 2.1.0
                        </p>
                        <p class="text-xs text-gray-400 mt-2 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            2 jam yang lalu
                        </p>
                    </div>
                    <div class="ml-2">
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            Info
                        </span>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- <!-- Notification Item 5 - Read -->
        <div class="p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer">
            <div class="ml-4">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-700 truncate">
                            Maintenance Terjadwal
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            Maintenance server akan dilakukan hari Senin pukul 02:00
                        </p>
                        <p class="text-xs text-gray-400 mt-2 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            1 hari yang lalu
                        </p>
                    </div>
                    <div class="ml-2">
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            Maintenance
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Item 6 - Read -->
        <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer">
            <div class="ml-4">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-700 truncate">
                            Laporan Bulanan
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            Laporan aktivitas bulan Mei telah tersedia untuk diunduh
                        </p>
                        <p class="text-xs text-gray-400 mt-2 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            2 hari yang lalu
                        </p>
                    </div>
                    <div class="ml-2">
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            Laporan
                        </span>
                    </div>
                </div>
            </div>
        </div> --}}
    </div> 
   
</div>