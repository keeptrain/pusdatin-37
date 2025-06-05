<div>
    <div class="flex flex-col gap-4">
        <flux:heading size="xl" level="1" class="text-color-testing-100">
            <span>Analytic Report</span>
        </flux:heading>
        <flux:text class="text-base">Export Data to Excel or Pdf</flux:text>

        <flux:separator variant="subtle" />
    </div>

    <div
        wire:loading
        wire:target="applyFilters"
        class="fixed inset-0 z-50 flex items-center justify-center" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="bg-white rounded-lg  mt-[48vh] mx-auto p-4 shadow-lg w-fit">
            <span class="text-base font-medium text-gray-700">
                Generate Process ....
            </span>
        </div>
    </div>
    <div
        wire:loading
        wire:target="exportHeadVerifier"
        class="fixed inset-0 z-50 flex items-center justify-center" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="bg-white rounded-lg  mt-[48vh] mx-auto p-4 shadow-lg w-fit">
            <span class="text-base font-medium text-gray-700">
                Generate Process ....
            </span>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-7">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Export Reports</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- PDF Report Card -->
            <x-export.pdf-card />

            <!-- Excel Report Card -->
            <x-export.excel-card />
        </div>
    </div>
    <!-- filter form -->
    <x-export.filter-form
        :statusOptions="$statusOptions"
        :status="$status"
        :statusOptionsPr="$statusOptionsPr"
        :source="$source" />
    <x-export.result-modal
        :show-modal=" $showModal"
        :start-date="$start_date"
        :end-date="$end_date"
        :status="$status"
        :source="$source" />
</div>