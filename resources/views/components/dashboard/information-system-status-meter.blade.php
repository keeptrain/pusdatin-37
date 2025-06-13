<h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-200 mb-3">Status Permohonan</h3>
<div class="flex-1 flex flex-col justify-center space-y-3 overflow-y-scroll">
    <div class="flex items-center">
        <div class="w-2 h-2 rounded-full bg-blue-500 mr-2"></div>
        <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Didisposisikan</div>
        <div class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['disposition'] }}
        </div>
    </div>
    <div class="flex items-center">
        <div class="w-2 h-2 rounded-full bg-amber-500 mr-2"></div>
        <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Revisi Kasatpel</div>
        <div class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['replied'] }}
        </div>
    </div>
    @hasanyrole('head_verifier')
    <div class="flex items-center">
        <div class="w-2 h-2 rounded-full bg-amber-500 mr-2"></div>
        <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Revisi Kapusdatin</div>
        <div class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['repliedKapusdatin'] }}
        </div>
    </div>
    @endhasanyrole
    <div class="flex items-center">
        <div class="w-2 h-2 rounded-full bg-green-500 mr-2"></div>
        <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Disetujui Kasatpel</div>
        <div class="font-medium text-neutral-800 dark:text-white">
            {{ $statusCounts['approvedKasatpel'] }}
        </div>
    </div>
</div>