@props([
    'title' => null,
    'model' => null,
    'required' => false,
    'optional' => false,
    'template' => false,
    'filePath' => null
])

<div class="flex flex-1 p-4 bg-zinc-50 justify-between border-t-1 border-l-1 border-r-1 rounded-t-lg mt-6">
    <h4 class="text-sm font-medium text-gray-700">
        {{ $title }}
        @if ($required)
            <span class="ml-2 px-2 py-1 text-xs bg-red-100 text-red-700 rounded-md">Wajib</span>
        @endif
        @if ($optional)
            <span class="ml-2 px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded-md">Menyusul</span>
        @endif
    </h4>
   
</div>

<div class="border-2 border-dashed hover:border-zinc-400 
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
            }" x-on:livewire-upload-start="uploading = true"
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
                <div class="p-w space-y-2">
                    <flux:button variant="outline" size="sm">Klik untuk upload</flux:button>
                    {{-- <p class="text-sm text-gray-500">atau drag and drop</p> --}}
                    <p class="text-xs text-gray-400 mt-1">hanya PDF, max 1MB</p>
                </div>
            </div>
        </template>

        <template x-if="fileName">
            <div class="flex items-start justify-between pl-2 pt-4 pb-4 pr-2">
                <div class="flex items-start gap-2 flex-1 min-w-0 mr-2">
                    <div class="flex-shrink-0">
                        <x-flux::icon.document />
                    </div>
                    
                    <span class="text-sm text-gray-700 break-words" x-text="fileName"></span>
                </div>
        
                <button type="button" x-on:click="
                        $wire.cancelUpload('{{ $model }}');
                        $wire.set('{{ $model }}', null);
                        fileName = '';
                        uploadedFilename = null;
                        progress = 0;
                        uploading = false;
                        $refs.fileInput.value = null;
                    " class="text-red-500 hover:text-red-700 flex-shrink-0 ml-auto">
                    <flux:icon.trash />
                </button>
            </div>
        </template>

        <!-- Progress Bar -->
        <template x-if="uploading">
            <div class="p-2">
                <div class="w-full bg-gray-200 rounded-md h-2.5">
                    <div class="bg-blue-300 h-2.5 rounded-md transition-all duration-200" :style="'width: ' + progress + '%'"></div>
                </div>
                <div class="text-xs text-gray-500 mt-1" x-text="progress + '%'"></div>
            </div>
        </template>
        
        <!-- Error Message -->
        @error($model)
            <p class="text-xs text-red-500 pl-4 pb-4">{{ $message }}</p>
        @enderror
    </div>
</div>

{{ $slot }}