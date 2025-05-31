<section>
    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-200 mb-3">Sistem Informasi & Data</h3>
    <div class="flex-1 flex flex-col justify-center space-y-3 overflow-y-scroll">
        <div class="flex items-center">
            <div class="w-2 h-2 rounded-full bg-green-500 mr-2"></div>
            <a href="{{ route('letter.table') }}"
                class="flex-1 text-sm text-neutral-600 dark:text-neutral-300 hover:underline">Menunggu Review
                Kapusdatin</a>
            <div class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['approvedKasatpel'] }}
            </div>
        </div>
        <div class="flex items-center">
            <div class="w-2 h-2 rounded-full bg-orange-500 mr-2"></div>
            <a href="{{ route('letter.table') }}"
                class="flex-1 text-sm text-neutral-600 dark:text-neutral-300 hover:underline">Revisi Kapusdatin</a>
            <div class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['repliedKapusdatin'] }}
            </div>
        </div>
        {{-- <div class="flex items-center">
            <div class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></div>
            <a href="{{ route('letter.table') }}"
                class="flex-1 text-sm text-neutral-600 dark:text-neutral-300 hover:underline">Disetujui Kapusdatin</a>
            <div class="font-medium text-neutral-800 dark:text-white">
                {{ $statusCounts['completed'] }}
            </div>
        </div> --}}
    </div>
</section>
<section class="mt-6">
    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-200 mb-3">Kehumasan</h3>
    <div class="flex-1 flex flex-col justify-center space-y-3 overflow-y-scroll">
        <div class="flex items-center">
            <div class="w-2 h-2 rounded-full bg-amber-500 mr-2"></div>
            <a href="{{ route('pr.index') }}"
                class="flex-1 text-sm text-neutral-600 dark:text-neutral-300 hover:underline">Menunggu Antrian
                Kapusdatin</a>
            <div class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['promkesCompleted'] }}
            </div>
        </div>
    </div>
</section>