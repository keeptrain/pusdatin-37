<h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-200 mb-3">Status Permohonan</h3>
<div class="flex-1 flex flex-col justify-center space-y-3">
    @hasrole('promkes_verifier')
    <div class="flex items-center">
        <div class="w-2 h-2 rounded-full bg-yellow-500 mr-2"></div>
        <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Permohonan Masuk</div>
        <div class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['pending'] }}
        </div>
    </div>
    <div class="flex items-center">
        <div class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></div>
        <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Antrean Promkes</div>
        <div class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['promkesQueue'] }}
        </div>
    </div>
    <div class="flex items-center">
        <div class="w-2 h-2 rounded-full bg-teal-700 mr-2"></div>
        <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Selesai Kurasi</div>
        <div class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['promkesCompleted'] }}
        </div>
    </div>
    @endhasrole
    @hasrole('pr_verifier')
    <div class="flex items-center">
        <div class="w-2 h-2 rounded-full bg-yellow-500 mr-2"></div>
        <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Antrean Pusdatin</div>
        <div class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['pusdatinQueue'] }}
        </div>
    </div>
    <div class="flex items-center">
        <div class="w-2 h-2 rounded-full bg-blue-500 mr-2"></div>
        <div class="flex-1 text-sm text-neutral-6005 dark:text-neutral-300">Proses Pusdatin</div>
        <div class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['pusdatinProcess'] }}
        </div>
    </div>
    <div class="flex items-center">
        <div class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></div>
        <div class="flex-1 text-sm text-neutral-6005 dark:text-neutral-300">Permohonan Selesai</div>
        <div class="font-medium text-neutral-800 dark:text-white">{{ $statusCounts['completed'] }}
        </div>
    </div>
    @endhasrole

</div>