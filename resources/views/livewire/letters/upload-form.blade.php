<x-letters.layout legend="Form Permohonan layanan">

    <!-- Section 1: Basic information -->
    <form wire:submit="save" class="space-y-6 mt-6">
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

                        <flux:input label="Penanggung jawab" placeholder="{{ auth()->user()->name }}" disabled />

                        <flux:input label="Kontak penanggung jawab" placeholder="{{ auth()->user()->contact }}" disabled />

                        <flux:input label="Seksi/Subbag/Subkel Pengusul" placeholder="{{ ucfirst(auth()->user()->section) }}" disabled />

                        <div class="grid grid-cols-2 gap-x-6">
                            <div>
                                <flux:input wire:model="reference_number" label="Nomor surat"
                                    placeholder="No./xx/xx/2025" clearable />
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 2: Document Upload -->
            <section>
                <div x-data="{
                    activeUploads: 0,
                    progress: 0,
                    get uploading() {
                        return this.activeUploads > 0;
                    }
                }" x-on:livewire-upload-start="activeUploads++" x-on:livewire-upload-finish="activeUploads--"
                    x-on:livewire-upload-error="activeUploads--" x-on:livewire-upload-cancel="activeUploads--"
                    x-on:livewire-upload-progress="progress = $event.detail.progress">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-md font-medium text-gray-700 mb-4 flex items-center">
                            <span
                                class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 inline-flex items-center justify-center mr-2">2</span>
                            Upload Dokumen
                        </h3>

                        <!-- File Upload -->
                        <x-letters.input-file-adapter title="Dokumen Identifikasi Aplikasi SPBE" model="files.0" required template
                            filePath="downloadTemplate('1')" />

                        <x-letters.input-file-adapter title="SOP Aplikasi SPBE"  model="files.1"
                            required template filePath="downloadTemplate('2')" />

                        <x-letters.input-file-adapter title="RFC Pusdatinkes" model="files.2" optional template
                            filePath="downloadTemplate('3')" />

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
                                    <span class="ml-2 text-sm font-medium text-gray-600">When is this required?</span>
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
                                <p class="text-sm">This section is optional and only needed for special cases requiring
                                    additional documentation. If your submission falls under standard procedures, you
                                    can
                                    skip this upload.</p>
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

            <flux:button type="submit" variant="primary">
                {{ __('Ajukan') }}
            </flux:button>
        </div>
    </form>

</x-letters.layout>