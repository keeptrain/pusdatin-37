{{-- Modal SOP --}}
<div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-start justify-center px-4 sm:px-6 mt-20" aria-labelledby="modal-title"
    role="dialog" aria-modal="true">

    {{-- Background overlay --}}
    <div class="fixed inset-0" style="background-color: rgba(0,0,0,0.6);" aria-hidden="true"></div>

    {{-- Modal panel --}}
    <div class="relative z-10  bg-white rounded-lg shadow-xl sm:max-w-lg w-full overflow-hidden">

        {{-- Header --}}
        <div class="px-6 py-5 text-center bg-amber-50 space-y-4">
            <div class="mx-auto mb-4 flex items-center justify-center h-12 w-12 rounded-full bg-amber-100">
                <x-lucide-file-text class="h-6 w-6 text-amber-600" />
            </div>
            <flux:heading size="lg">SOP Permohonan Sistem Informasi & Data</flux:heading>

            <p class="mt-2 text-base text-gray-800">
                Silakan download dan baca SOP terlebih dahulu sebelum mengisi form permohonan.
            </p>
            <flux:button @click="downloadSOP()" icon="arrow-down-tray">Download SOP</flux:button>
        </div>

        {{-- Body --}}
        <div class="px-6 pb-5 text-center">
            {{-- Checkbox confirmation --}}
            <div class="mt-6 text-left">
                <label class="flex items-start space-x-3">
                    <flux:checkbox x-model="sopConfirmed"></flux:checkbox>
                    <span class="text-sm text-gray-700">
                        Saya sudah membaca dan memahami SOP Permohonan Sistem Informasi & Data
                    </span>
                </label>
            </div>
        </div>

        {{-- Footer --}}
        <div class=" px-6 py-4 flex justify-end flex-col-reverse gap-1 sm:flex-row sm:justify-end sm:space-x-3">
            <flux:button @click="closeModal()" variant="ghost">Batal</flux:button>

            <button @click="confirmReadSOP()" :disabled="!sopConfirmed" :class="sopConfirmed ? 'bg-[#364872] hover:bg-[#2c3751] focus:ring-[#364872]' : 'bg-gray-300 cursor-not-allowed'"
                class="sm:w-auto justify-center rounded-md px-4 py-2 text-sm font-medium text-white focus:outline-none transition">
                Lanjut Isi Form
            </button>
        </div>
    </div>
</div>