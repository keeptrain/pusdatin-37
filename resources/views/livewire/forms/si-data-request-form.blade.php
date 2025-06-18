<x-layouts.form.request legend="Form Permohonan layanan" nameForm="Sistem Informasi & Data">
    {{-- <div class="bg-blue-50 border-blue-400 text-blue-800 p-4 rounded-md shadow-sm flex items-start space-x-3 mt-4"
        role="alert">
        <div>
            <h4 class="font-bold text-lg mb-1">Penting!</h4>
            <p class=" text-base">
                Anda harus membaca dan memahami Standar Operasional Prosedur (SOP) sebelum
                mengajukan permohonan.
                <a wire:click="downloadSOP" class="underline font-bold hover:text-blue-900 cursor-pointer">Download
                    disini</a>
            </p>
        </div>
    </div> --}}
    <!-- Section 1: Basic information -->
    <form x-data="{
        activeUploads: 0,
        progress: 0,
        get uploading() {
            return this.activeUploads > 0;
        } 
        }" wire:submit="save" class="space-y-6 mt-6">
        <div class="grid lg:grid-cols-2 gap-4">
            <section>
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-md font-medium text-gray-700 mb-4 flex items-center">
                        <span
                            class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 inline-flex items-center justify-center mr-2">1</span>
                        Informasi Dasar
                    </h3>

                    <div class="space-y-6">
                        <flux:input wire:model="title" label="Judul" placeholder="Judul permohonan layanan" clearable />

                        <div class="grid grid-cols-2 gap-x-6">
                            <div>
                                <flux:input wire:model="reference_number" label="Nomor surat"
                                    placeholder="No./xx/xx/2025" clearable />
                            </div>
                        </div>

                        <flux:input label="Penanggung jawab" placeholder="{{ auth()->user()->name }}" disabled />

                        <flux:input label="Kontak penanggung jawab" placeholder="{{ auth()->user()->contact }}"
                            disabled />

                        <flux:input label="Seksi/Subbag/Subkel Pengusul"
                            placeholder="{{ ucfirst(auth()->user()->section) }}" disabled />

                    </div>
                </div>
            </section>

            <!-- Section 2: Document Upload -->
            <section>
                <div x-on:livewire-upload-start="activeUploads++" x-on:livewire-upload-finish="activeUploads--"
                    x-on:livewire-upload-error="activeUploads--" x-on:livewire-upload-cancel="activeUploads--"
                    x-on:livewire-upload-progress="progress = $event.detail.progress">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-md font-medium text-gray-700 mb-4 flex items-center">
                            <span
                                class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 inline-flex items-center justify-center mr-2">2</span>
                            Kelengkapan Dokumen
                        </h3>

                        <x-letters.input-file-adapter title="Permohonan (nota dinas)" model="files.0" required />

                        <!-- File Upload -->
                        <x-letters.input-file-adapter title="1. Dokumen Identifikasi Aplikasi" model="files.1" required
                            template filePath="downloadTemplate('1')" />

                        <x-letters.input-file-adapter title="2. SOP Aplikasi" model="files.2" required template
                            filePath="downloadTemplate('2')" />

                        <x-letters.input-file-adapter title="3. Pakta Integritas Implementasi" model="files.3" required
                            template filePath="downloadTemplate('3')" />

                        <x-letters.input-file-adapter title="4. Form RFC Pusdatinkes" model="files.4" required template
                            filePath="downloadTemplate('4')" />

                        <x-letters.input-file-adapter title="5. Surat perjanjian kerahasiaan" model="files.5" optional
                            template filePath="downloadTemplate('5')" />

                        <div x-data="{ open: false }" class="border-l-2 border-gray-400 bg-gray-50 ">
                            <div class="p-2 flex justify-between items-center cursor-pointer" @click="open = !open">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="16" x2="12" y2="12"></line>
                                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                    </svg>
                                    <span class="ml-2 text-sm font-medium text-gray-600">Kapan ini dibutuhkan?</span>
                                </div>
                                <svg x-show="!open" class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                                <svg x-show="open" class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="18 15 12 9 6 15"></polyline>
                                </svg>
                            </div>

                            <div x-show="open" class="p-3 text-gray-600 border-t border-gray-200">
                                <p class="text-sm">Jika sudah mendapatkan tanda tangan beserta stempel</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="flex flex-row justify-between mt-4">
            <flux:button type="button" :href="route('dashboard')" wire:navigate>
                {{ __('Cancel') }}
            </flux:button>

            <flux:button type="submit" variant="primary" x-bind:disabled="uploading">
                {{ __('Ajukan') }}
            </flux:button>
        </div>
    </form>
</x-layouts.form.request>