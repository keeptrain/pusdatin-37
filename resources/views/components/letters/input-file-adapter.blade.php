{{-- @props([
'title' => null,
'model' => null
]) --}}

<h4 class="text-sm font-medium text-gray-700 mb-2">{{ $title }}</h4>
<div
    class="border-2 rounded-lg p-6 border-dashed hover:border-accent-content 
    {{ $errors->has($model) ? 'border-red-500 ' : 'border-gray-300 ' }} cursor-pointer">
    <div x-data="{
        uploading: false,
        progress: 0,
        fileName: '',
        uploadedFilename: null,
        handleDrop(e) {
            e.preventDefault();
            const files = e.dataTransfer.files;
            if (files.length) {
                $refs.fileInput.files = files;
                $refs.fileInput.dispatchEvent(new Event('change'));
                this.fileName = files[0].name;
            }
        }
            }" 
        x-on:livewire-upload-start="uploading = true"
        x-on:livewire-upload-finish.prevent="event => { uploadedFilename = event.detail.uploadedFilename; uploading = false; }"
        x-on:livewire-upload-error="uploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress"
        class="w-full">

        <!-- Hidden Input -->
        <input type="file" wire:model="{{ $model }}" wire:key="input-file-{{ $model }}" class="hidden" x-ref="fileInput"
            x-on:change="fileName = $event.target.files[0]?.name">

        <!-- Drop Zone -->
        <template x-if="!fileName">
            <div class="flex items-center justify-center w-full px-4 py-3 text-center transition bg-white cursor-pointer focus:outline-none"
                x-on:dragover.prevent x-on:drop="handleDrop" x-on:click="$refs.fileInput.click()">
                <div>
                    <p class="text-sm text-black font-black">Click to upload
                        <span class="font-normal">or drag and drop</span>
                    </p>
                    <p class="text-xs text-gray-500">PDF only, max 1MB</p>
                </div>
            </div>
        </template>

        <!-- File Preview -->
        <template x-if="fileName">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <!-- Replace with your icon -->
                    <x-flux::icon.document />

                    <span class="text-sm text-gray-700 truncate" x-text="fileName"></span>
                </div>
                <button type="button" x-on:click="
                        $wire.cancelUpload('{{ $model }}');
                        $wire.set('{{ $model }}', null);
                        fileName = '';
                        uploadedFilename = null;
                        progress = 0;
                        uploading = false;
                        $refs.fileInput.value = null;
                " class="text-red-500 hover:text-red-700">
                    <flux:icon.trash />
                </button>

            </div>
        </template>

        <!-- Progress Bar -->
        <template x-if="uploading">

            <div class="mt-2">
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-blue-500 h-2.5 rounded-full transition-all duration-200" :style="'width: ' + progress + '%'"></div>
                </div>
                <div class="text-xs text-gray-500 mt-1" x-text="progress + '%'"></div>
            </div>
        </template>
        <!-- Error Message -->
        @error($model)
            <p class="text-xs text-red-500 mt-3">{{ $message }}</p>
        @enderror
    </div>
</div>