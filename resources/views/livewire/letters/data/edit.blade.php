<div>
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Update</h2>

    <form wire:submit="save" class="space-y-6">

        <!-- Section 1: Basic Information -->
        <div class="border border-gray-200 rounded-lg p-4">
            <h3 class="text-md font-medium text-gray-700 mb-4 flex items-center">
                <span
                    class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 inline-flex items-center justify-center mr-2">1</span>
                Informasi Dasar
            </h3>

            <div class="space-y-6">
                <flux:input wire:model="title" label="Title" placeholder="Nama lengkap" clearable />

                <flux:input wire:model="responsible_person" label="Responsible person" placeholder="Nama lengkap"
                    clearable />

                <div class="grid grid-cols-2 gap-x-6">

                    <div>
                        <flux:input wire:model="reference_number" label="Reference number" placeholder="Nama lengkap"
                            clearable />
                    </div>

                    <div>
                        <flux:select wire:model="section" label="Section" placeholder="Choose section...">
                            <flux:select.option>Seksi A</flux:select.option>
                            <flux:select.option>Seksi B</flux:select.option>
                            <flux:select.option>Seksi C</flux:select.option>
                            <flux:select.option>Seksi D</flux:select.option>
                        </flux:select>
                    </div>
                </div>

            </div>
        </div>

        <!-- Section 2: Document Upload -->
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
            }" x-on:livewire-upload-start="activeUploads++" x-on:livewire-upload-finish="activeUploads--"
                x-on:livewire-upload-error="activeUploads--" x-on:livewire-upload-cancel="activeUploads--"
                x-on:livewire-upload-progress="progress = $event.detail.progress" class="space-y-6">
                @foreach ($letter->mapping as $item)
                    @php
                        $upload = $item->letterable;
                    @endphp

                    @if ($upload instanceof \App\Models\Letters\LetterUpload && $upload->needs_revision)

                        <!-- File Upload -->
                        <div>
                            <x-letters.input-file-adapter title="{{ $upload->part_name }}"
                                model="revisedFiles.{{ $upload->part_name }}" />
                            <label class="block text-sm font-medium text-zinc-400 mt-2 mb-2">
                                Note: {{ $upload->revision_note }}</label>
                        </div>

                        {{-- <div
                            class="border-2 rounded-lg p-6 {{ $errors->has('revisedFiles.' . $upload->part_name) ? 'border-dashed border-red-500' : 'border-dashed border-gray-300' }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-700 mb-1">{{ $upload->part_name }}</h4>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Note:
                                            {{ $upload->revision_note }}</label>
                                        <flux:input wire:model="revisedFiles.{{ $upload->part_name }}" type="file"
                                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />

                                        @error("revisedFiles.{$upload->part_name}")
                                        <p class="text-xs text-red-500 mt-3">{{ $message }}</p>
                                        @enderror

                                        <p class="text-xs text-gray-500 mt-6">Format: PDF (Max. 1MB)</p>
                                    </div>
                                </div>

                                <div class="ml-4">
                                    <div class="flex items-center text-sm text-gray-500 mb-1">
                                        <span class="font-medium">Version:</span>
                                        <span class="ml-1">{{ $upload->version }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Diperbarui: {{ $upload->updated_at }}
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Section 3: Additional Info -->
        <div class="border border-gray-200 rounded-lg p-4">
            <h3 class="text-md font-medium text-gray-700 mb-4 flex items-center">
                <span
                    class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 inline-flex items-center justify-center mr-2">3</span>
                Informasi Tambahan
            </h3>

            <div class="space-y-4">
                <div>
                    <flux:textarea wire:model="revisionNote" placeholder="Input information of changes...">
                    </flux:textarea>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-3">
            <flux:button href="{{ route('letter.table') }}">
                Cancel
            </flux:button>
            <flux:button type="submit" variant="primary">
                Update
            </flux:button>
        </div>
    </form>
</div>