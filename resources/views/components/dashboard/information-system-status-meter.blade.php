<flux:heading size="lg" class="mb-3">Status Permohonan</flux:heading>
<div class="grid grid-cols-1 md:grid-cols-2 gap-2">
    <!-- Kolom Kiri -->
    <div class="flex flex-col space-y-2">
        <div class="flex items-center">
            <div class="w-2 h-2 rounded-full bg-zinc-400 mr-2"></div>
            <flux:text>
                Didisposisikan:
                <span class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['disposition'] }}</span>
            </flux:text>
        </div>
        <div class="flex items-center">
            <div class="w-2 h-2 rounded-full bg-zinc-400 mr-2"></div>
            <flux:text>
                Revisi Kasatpel:
                <span class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['replied'] }}</span>
            </flux:text>
        </div>
        @hasanyrole('head_verifier')
        <div class="flex items-center">
            <div class="w-2 h-2 rounded-full bg-zinc-400 mr-2"></div>
            <flux:text>
                Revisi Kapusdatin:
                <span
                    class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['repliedKapusdatin'] }}</span>
            </flux:text>
        </div>
        @endhasanyrole
    </div>

    <!-- Kolom Kanan -->
    <div class="flex flex-col space-y-2">
        <div class="flex items-center">
            <div class="w-2 h-2 rounded-full bg-zinc-400 mr-2"></div>
            <flux:text>
                Disetujui Kasatpel:
                <span
                    class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['approvedKasatpel'] }}</span>
            </flux:text>
        </div>
        <div class="flex items-center">
            <div class="w-2 h-2 rounded-full bg-zinc-400 mr-2"></div>
            <flux:text>
                Proses:
                <span class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['process'] }}</span>
            </flux:text>
        </div>
    </div>
</div>