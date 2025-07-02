<section>
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Update permohonan layanan</h2>

    <form wire:submit="save" class="space-y-6">
        <div class="grid lg:grid-cols-2 gap-4">
            <section>
                <!-- Section 1: Basic Information -->
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
            @if ($this->checkDocumentUploadNeedRevision)
                <section>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-md font-medium text-gray-700 mb-4 flex items-center">
                            <span
                                class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 inline-flex items-center justify-center mr-2">2</span>
                            Unggah Dokumen
                        </h3>

                        <div x-data="{
                                    activeUploads: 0,
                                    progress: 0,
                                    get uploading() {
                                        return this.activeUploads > 0;
                                    }
                                }" x-on:livewire-upload-start="activeUploads++"
                            x-on:livewire-upload-finish="activeUploads--" x-on:livewire-upload-error="activeUploads--"
                            x-on:livewire-upload-cancel="activeUploads--"
                            x-on:livewire-upload-progress="progress = $event.detail.progress" class="space-y-6">

                            @foreach ($systemRequest->documentUploads as $documentUpload)
                                @if ($documentUpload->need_revision)
                                    <section>
                                        <x-letters.input-file-adapter :title="$documentUpload->part_number_label"
                                            model="revisedFiles.{{ $documentUpload->part_number }}" required />
                                        @foreach ($documentUpload->load('versions')->versions->where('is_resolved', false) as $revision)
                                            <x-letters.warning-note :note="$revision->revision_note" />
                                        @endforeach
                                    </section>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            <!-- Section 3: Additional Info -->
            <section class="col-start-2">
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-md font-medium text-gray-700 mb-4 flex items-center">
                        <span
                            class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 inline-flex items-center justify-center mr-2">3</span>
                        Informasi Tambahan
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <flux:textarea wire:model="notes"
                                placeholder="Masukkan informasi tambahan tentang perubahan">
                            </flux:textarea>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-between">
            <flux:button href="{{ route('detail.request', ['type' => 'information-system', $systemRequestId]) }}">
                Cancel
            </flux:button>
            <flux:button type="submit" variant="primary">
                Update
            </flux:button>
        </div>
    </form>
</section>