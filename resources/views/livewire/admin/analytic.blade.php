<div class="lg:p-3">
    <!-- Full page overlay loading -->
    {{-- <div wire:loading.delay wire:target="exportAsPdf, exportAsExcel">
        <div class="fixed inset-0 z-50 flex items-center justify-center" style="background-color: rgba(0, 0, 0, 0.5);">
            <div class="bg-white p-6 rounded-lg shadow-xl text-center">
                <i class="fas fa-spinner fa-spin fa-3x text-blue-500 mb-4"></i>
                <h2 class="text-xl font-semibold">Generating Report</h2>
                <p class="mt-2">Harap tunggu sedang menyiapkan file laporan...</p>
            </div>
        </div>
    </div> --}}

    <div class="space-y-6">
        <div class="flex flex-col gap-4">
            <flux:heading size="xl" level="1" class="text-color-testing-100">
                <span>Laporan Analitik</span>
            </flux:heading>
            {{-- <flux:text class="text-base">Export data dalam format pdf atau excel</flux:text> --}}
            <flux:separator variant="subtle" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- PDF Report Card -->
            <x-exports.pdf-card />

            <!-- Excel Report Card -->
            <x-exports.excel-card />
        </div>

        <!-- Filter form -->
        <x-exports.filter-form :statusOptions="$statusOptions" :status="$status" :statusOptionsPr="$statusOptionsPr" />

        <!-- Show modal if needed filter -->
        @if($showModal)
            <x-exports.result-modal :startAt="$startAt" :endAt="$endAt" :status="$status" :service="$service" />
        @endif
    </div>
</div>