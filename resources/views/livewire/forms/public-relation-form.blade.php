<x-layouts.form.request legend="Form Permohonan layanan" nameForm="Kehumasan">

    <!-- Section 1: Basic information -->
    <form wire:submit="save" class="space-y-6 mt-6">
        <div class="grid lg:grid-cols-2 gap-4">
            <section>
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-md font-medium text-gray-700 mb-4 flex items-center">
                        <span
                            class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 inline-flex items-center justify-center mr-2">1</span>
                        Basic information
                    </h3>

                    <div class="space-y-6">
                        <flux:input label="Penanggung Jawab" placeholder="{{ auth()->user()->name }}" disabled />

                        <flux:input label="Kontak Penanggung Jawab" placeholder="{{ auth()->user()->contact }}"
                            disabled />

                        <flux:input label="Seksi/Subbag/Subkel Pengusul"
                            placeholder="{{ ucfirst(auth()->user()->section) }}" disabled />

                        <flux:select wire:model="monthPublication" label="Bulan usulan publikasi"
                            placeholder="Pilih bulan...">
                            <flux:select.option value="1">Januari</flux:select.option>
                            <flux:select.option value="2">Februari</flux:select.option>
                            <flux:select.option value="3">Maret</flux:select.option>
                            <flux:select.option value="4">April</flux:select.option>
                            <flux:select.option value="5">Mei</flux:select.option>
                            <flux:select.option value="6">Juni</flux:select.option>
                            <flux:select.option value="7">Juli</flux:select.option>
                            <flux:select.option value="8">Agustus</flux:select.option>
                            <flux:select.option value="9">September</flux:select.option>
                            <flux:select.option value="10">Oktober</flux:select.option>
                            <flux:select.option value="11">November</flux:select.option>
                            <flux:select.option value="12">Desember</flux:select.option>
                        </flux:select>

                        <flux:input wire:model="spesificDate" label="Tanggal Spesifik Publikasi Media"
                            description="Khusus pada usulan untuk Hari Besar Kesehatan" placeholder="Umum"
                            type="date" />

                        <flux:input wire:model="theme" label="Tema pesan kesehatan"
                            description="Berdasarkan tema umum atau tema khusus pada peringatan hari kesehatan atau lainnya"
                            placeholder="contoh: KIA, Kesehatan Jiwa, Hari Gizi Nasional, Germas, TB TPT" />
                    </div>
                </div>
            </section>

            <!-- Section 2: Document Upload -->
            <div x-data="{
                activeUploads: null,
                selectedMediaType: $wire.mediaType,
                uploadedFiles: { },
                otherValue: false,
                progress: 0,
                    get uploading() {
                        return this.activeUploads > 0;
                    }
                }" x-on:livewire-upload-start="activeUploads++" x-on:livewire-upload-finish="activeUploads--"
                x-on:livewire-upload-error="activeUploads--" x-on:livewire-upload-cancel="activeUploads--"
                x-on:livewire-upload-progress="progress = $event.detail.progress"
                class="space-y-6 border border-gray-200 rounded-lg p-4">

                <flux:radio.group wire:model="target" label="Sasaran">
                    <flux:radio label="Masyarakat Umum" value="masyarakat_umum" />
                    <flux:radio label="Tenaga Kesehatan" value="tenaga_kesehatan" />
                    <flux:radio label="Anak Sekolah" value="anak_sekolah" />
                    <div class="flex items-center">
                        <flux:radio label="Other:" value="other" />
                        <!-- Input Field for "Other" -->
                        <input type="text"
                            class="ml-2 border-b border-gray-300 focus:border-blue-500 focus:outline-none py-1 px-1 w-60" />
                    </div>
                    @error('otherTarget')
                        <flux:text class="text-md text-red-500">{{ $message }}</flux:text>
                    @enderror
                </flux:radio.group>

                <flux:checkbox.group wire:model="mediaType" label="Jenis Media yang Diusulkan"
                    x-model="selectedMediaType">
                    <div class="grid grid-cols-2 gap-8">
                        <div class="flex flex-col space-y-2">
                            <flux:checkbox label="Audio" value="1" />
                            <flux:checkbox label="Infografis" value="2" />
                            <flux:checkbox label="Poster" value="3" />
                            <flux:checkbox label="Video" value="4" />
                            <flux:checkbox label="Bumper" value="5" />
                            <flux:checkbox label="Backdrop Kegiatan" value="6" />
                        </div>
                    
                        <div class="flex flex-col space-y-2">
                            <flux:checkbox label="Spanduk" value="7" />
                            <flux:checkbox label="Roll Banner" value="8" />
                            <flux:checkbox label="Sertifikat" value="9" />
                            <flux:checkbox label="Press Release" value="10" />
                            <flux:checkbox label="Artikel" value="11" />
                        </div>
                    </div>
                </flux:checkbox.group>

                <h3 class="text-md font-medium text-gray-700 mb-4 flex items-center">
                    <span
                        class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 inline-flex items-center justify-center mr-2">2</span>
                    Upload document
                </h3>
                <div class="p-2 bg-amber-100  items-center rounded-lg">
                    <div class="flex items-center">
                        <flux:icon.arrow-down-circle class="text-amber-600 dark:text-amber-300" />
                        <flux:heading wire:click="downloadTemplate"
                            class="ml-2 text-amber-800 underline cursor-pointer">Download template
                        </flux:heading>
                    </div>
                    <flux:subheading size="sm" class="ml-8">Setiap materi menggunakan template yang sama.
                    </flux:subheading>
                </div>

                <section>
                    <template x-if="selectedMediaType.includes('1')">
                        <div>
                            <x-letters.input-file-adapter title="Materi Audio" model="uploadFile.1" required />
                        </div>
                    </template>
                    <template x-if="selectedMediaType.includes('2')">
                        <div>
                            <x-letters.input-file-adapter title="Materi Infographics" model="uploadFile.2" required />
                        </div>
                    </template>
                    <template x-if="selectedMediaType.includes('3')">
                        <div>
                            <x-letters.input-file-adapter title="Materi Poster" model="uploadFile.3" required />
                        </div>
                    </template>
                    <template x-if="selectedMediaType.includes('4')">
                        <div>
                            <x-letters.input-file-adapter title="Materi Video" model="uploadFile.4" required />
                        </div>
                    </template>
                    <template x-if="selectedMediaType.includes('5')">
                        <div>
                            <x-letters.input-file-adapter title="Materi Bumper" model="uploadFile.5" required />
                        </div>
                    </template>
                    <template x-if="selectedMediaType.includes('6')">
                        <div>
                            <x-letters.input-file-adapter title="Materi Backdrop Kegiatan" model="uploadFile.6" required />
                        </div>
                    </template>
                    <template x-if="selectedMediaType.includes('7')">
                        <div>
                            <x-letters.input-file-adapter title="Materi Spanduk" model="uploadFile.7" required />
                        </div>
                    </template>
                    <template x-if="selectedMediaType.includes('8')">
                        <div>
                            <x-letters.input-file-adapter title="Materi Roll banner" model="uploadFile.8" required />
                        </div>
                    </template>
                    <template x-if="selectedMediaType.includes('9')">
                        <div>
                            <x-letters.input-file-adapter title="Materi Sertifikat" model="uploadFile.9" required />
                        </div>
                    </template>
                    <template x-if="selectedMediaType.includes('10')">
                        <div>
                            <x-letters.input-file-adapter title="Materi Press Release" model="uploadFile.10" required />
                        </div>
                    </template>
                    <template x-if="selectedMediaType.includes('11')">
                        <div>
                            <x-letters.input-file-adapter title="Materi Artikel" model="uploadFile.11" required />
                        </div>
                    </template>
                    
                    <template x-if="selectedMediaType === null || selectedMediaType.length === 0">
                        <div class="bg-blue-50 border-blue-400 text-blue-800 p-4 rounded-md shadow-sm flex items-start space-x-3"
                            role="alert">
                            <div>
                                <h4 class="font-bold text-lg mb-1">Perhatian!</h4>
                                <p class=" text-base">
                                    Sepertinya Anda belum memilih **jenis media yang diusulkan** di bagian atas.
                                    Silakan pilih setidaknya satu jenis media untuk dapat mengunggah dokumen terkait.
                                </p>
                            </div>
                        </div>
                    </template>
                </section>
            </div>
        </div>

        <div class="flex flex-row justify-between mt-4">
            <flux:button type="button" :href="route('letter')" wire:navigate>
                {{ __('Cancel') }}
            </flux:button>

            <flux:button type="submit" variant="primary">
                {{ __('Ajukan') }}
            </flux:button>
        </div>
    </form>

</x-layouts.form.request>