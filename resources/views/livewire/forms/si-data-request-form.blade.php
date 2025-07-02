<x-layouts.form.request legend="Form Permohonan layanan" nameForm="Sistem Informasi & Data">
    <form x-data="{
        hasPartNumber5: false,
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

                        <flux:callout icon="question-mark-circle" variant="secondary" inline class="mt-6">
                            <flux:callout.heading>Apakah anda sudah memiliki surat perjanjian kerahasiaan (NDA)?
                            </flux:callout.heading>
                            <div class="flex items-start">
                                <flux:checkbox @click="hasPartNumber5 = !hasPartNumber5" />
                                <flux:callout.text class="ml-2">Ya, saya sudah memiliki dan siap untuk di kirim (silahkan upload surat perjanjian kerahasiaan di bagian 5).
                                </flux:callout.text>
                            </div>
                        </flux:callout>

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
                        <x-letters.input-file-adapter title="1. Dokumen Identifikasi Aplikasi" model="files.1"
                            required />

                        <x-letters.input-file-adapter title="2. SOP Aplikasi" model="files.2" required />

                        <x-letters.input-file-adapter title="3. Pakta Integritas Implementasi" model="files.3"
                            required />

                        <x-letters.input-file-adapter title="4. Form RFC Pusdatinkes" model="files.4" required />

                        <template x-if="hasPartNumber5">
                            <div class="mt-4">
                                <x-letters.input-file-adapter title="5. Surat perjanjian kerahasiaan" model="files.5"
                                    optional />
                            </div>
                        </template>
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