<!-- Notifications List (Right - 1 column) -->
<div class="space-y-4 p-4">
    <!-- Notifications Header -->
    <div class="  border-gray-200 space-y-2">
        <div class="flex items-center justify-between">
            <flux:heading size="xl" level="1" class="text-testing-100">Notifikasi</flux:heading>
            {{-- <flux:button size="sm" variant="subtle">
                Tandai semua dibaca
            </flux:button>
            <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                {{ $notifications->count() }} Baru
            </span> --}}
        </div>
    </div>

    <!-- Notifications List -->
    <div class="max-h-80 h-80 overflow-y-auto">
        <livewire:admin.notifications :dashboardUser="true" />

    </div>
</div>