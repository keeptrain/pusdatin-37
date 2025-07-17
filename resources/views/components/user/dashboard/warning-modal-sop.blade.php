{{-- Modal SOP --}}
<div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-start justify-center px-4 sm:px-6 mt-20 overflow-y-auto" aria-labelledby="modal-title"
    role="dialog" aria-modal="true" x-cloak>

    {{-- Background overlay --}}
    <div class="fixed inset-0" style="background-color: rgba(0,0,0,0.6);" aria-hidden="true"></div>

    {{-- Modal panel --}}
    <div class="relative z-10 bg-white rounded-lg shadow-xl sm:max-w-lg w-full max-h-screen overflow-y-auto">
        <div class="p-6 space-y-4">
            <!-- Requirements List -->
            <div class="space-y-4">
                <flux:heading size="xl">Perhatian!</flux:heading>
                <flux:subheading size="md">Sebelum mengajukan, harap memahami syarat & ketentuan di
                    bawah ini.</flux:subheading>

                <div class="space-y-3">
                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div
                            class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-blue-600 font-bold text-sm">1</span>
                        </div>
                        <div class="flex flex-col items-start space-x-2">
                            <p class="text-gray-700 text-sm leading-relaxed">Unduh dokumen SOP & template terbaru</p>
                            <flux:button size="sm" href="{{ route('download.sop-and-templates') }}"
                                icon="arrow-down-tray">
                                Download</flux:button>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div
                            class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-blue-600 font-bold text-sm">2</span>
                        </div>
                        <p class="text-gray-700 text-sm leading-relaxed">Membaca dan memahami SOP secara keseluruhan</p>
                    </div>
                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div
                            class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-blue-600 font-bold text-sm">3</span>
                        </div>
                        <p class="text-gray-700 text-sm leading-relaxed">Memahami template yang telah disediakan, berada
                            pada folder templates</p>
                    </div>
                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div
                            class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-blue-600 font-bold text-sm">4</span>
                        </div>
                        <p class="text-gray-700 text-sm leading-relaxed">Mengisi form kelengkapan dokumen sesuai dengan
                            template yang telah disediakan </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 pb-5 text-center">
            {{-- Checkbox confirmation --}}
            <div class="text-left">
                <label class="flex items-start space-x-3">
                    <flux:checkbox x-model="sopConfirmed" checked></flux:checkbox>
                    <span class="text-sm text-gray-700">
                        Saya sudah membaca dan memahami SOP Permohonan Sistem Informasi & Data
                    </span>
                </label>
            </div>
        </div>

        {{-- Footer --}}
        <div class=" px-6 py-4 flex justify-end flex-col-reverse gap-1 sm:flex-row sm:justify-end sm:space-x-3">
            <flux:button @click="closeModal()" variant="subtle">Batal</flux:button>

            <flux:button @click="confirmReadSOP()" variant="primary" x-bind:disabled="!sopConfirmed">
                Isi Form
            </flux:button>
        </div>
    </div>
</div>