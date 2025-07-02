<div class="space-y-6">
    <div class="flex flex-col gap-4">
        <flux:heading size="xl" level="1" class="text-color-testing-100">
            <span>Laporan Analitik</span>
        </flux:heading>
        <flux:text class="text-base">Export data dalam format pdf atau excel</flux:text>

        <flux:separator variant="subtle" />
    </div>

    <div wire:loading wire:target="exportAsExcel" class="fixed inset-0 z-50 flex items-center justify-center"
        style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="bg-white rounded-lg  mt-[48vh] mx-auto p-4 shadow-lg w-fit">
            <span class="text-base font-medium text-gray-700">
                Generate Process ....
            </span>
        </div>
    </div>
    <div wire:loading wire:target="exportAsExcel" class="fixed inset-0 z-50 flex items-center justify-center"
        style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="bg-white rounded-lg  mt-[48vh] mx-auto p-4 shadow-lg w-fit">
            <span class="text-base font-medium text-gray-700">
                Generate Process ....
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- PDF Report Card -->
        <x-exports.pdf-card />

        <!-- Excel Report Card -->
        <x-exports.excel-card />
    </div>

    <!-- filter form -->
    <x-exports.filter-form :statusOptions="$statusOptions" :status="$status" :statusOptionsPr="$statusOptionsPr" />

    @if($showModal)
        <x-exports.result-modal :startAt="$startAt" :endAt="$endAt" :status="$status" :service="$service" />
    @endif
</div>