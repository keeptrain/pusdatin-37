<flux:heading size="lg" class="mb-3">Status Permohonan</flux:heading>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Kolom Kiri -->
    <div class="flex flex-col space-y-2">
        @hasrole('promkes_verifier')
        <div class="flex items-center">
            <div class="w-2 h-2 rounded-full bg-zinc-400  mr-2"></div>
            <flux:text>
                Usulan Masuk:
                <span class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['pending'] }}</span>
            </flux:text>
        </div>
        <div class="flex items-center">
            <div class="w-2 h-2 rounded-full bg-zinc-400 mr-2"></div>
            <flux:text>
                Antrean Promkes:
                <span class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['promkesQueue'] }}</span>
            </flux:text>
        </div>
        <div class="flex items-center">
            <div class="w-2 h-2 rounded-full bg-zinc-400 mr-2"></div>
            <flux:text>
                Selesai Kurasi:
                <span
                    class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['promkesCompleted'] }}</span>
            </flux:text>
        </div>
        @endhasrole
        @hasrole('pr_verifier')
        <div class="flex items-center">
            <div class="w-2 h-2 rounded-full bg-zinc-400 mr-2"></div>
            <flux:text>
                Antrean Pusdatin:
                <span class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['pusdatinQueue'] }}</span>
            </flux:text>
        </div>
        <div class="flex items-center">
            <div class="w-2 h-2 rounded-full bg-zinc-400 mr-2"></div>
            <flux:text>
                Proses:
                <span class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['pusdatinProcess'] }}</span>
            </flux:text>
        </div>
        <div class="flex items-center">
            <div class="w-2 h-2 rounded-full bg-zinc-400 mr-2"></div>
            <flux:text>
                Selesai:
                <span class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['completed'] }}</span>
            </flux:text>
        </div>
        @endhasrole
    </div>

    {{-- <!-- Kolom Kanan -->
    <div class="flex flex-col space-y-2">
        <div class="flex items-center">
            <div class="w-2 h-2 rounded-full bg-zinc-400 mr-2"></div>
            <flux:text>
                Disetujui Kasatpel:
                <span class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['approvedKasatpel']
                    }}</span>
            </flux:text>
        </div>
        <div class="flex items-center">
            <div class="w-2 h-2 rounded-full bg-zinc-400 mr-2"></div>
            <flux:text>
                Proses:
                <span class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['process'] }}</span>
            </flux:text>
        </div>
    </div> --}}
</div>