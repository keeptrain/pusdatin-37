<div class="border border-gray-200 rounded-lg p-4">
    <div class="flex items-center space-x-3 mb-4">
        <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                        clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>
        <div class="flex-1">
            <h3 class="text-base font-medium text-gray-900 mb-1">PDF Report</h3>
        </div>
    </div>

    <p class="text-sm text-gray-700 mb-4">
        Klik tombol Export pdf untuk mendapatkan seluruh laporan permohonan sesuai akun anda.
    </p>
    {{-- <a href="{{ route('export.head_verifier.pdf') }}" class="w-full">Export PDF</a> --}}
    <flux:button wire:click="exportAsPdf" icon="arrow-down-tray" variant="outline" class="w-full">Export PDF
    </flux:button>
</div>