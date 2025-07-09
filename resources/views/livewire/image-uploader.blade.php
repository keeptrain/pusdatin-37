<div x-data="{ 
        uploading: false, 
        progress: 0
    }" x-on:livewire-upload-start="uploading = true; progress = 0"
    x-on:livewire-upload-finish="uploading = false; progress = 0"
    x-on:livewire-upload-error="uploading = false; progress = 0"
    x-on:livewire-upload-progress="progress = $event.detail.progress">

    <div class="space-y-2">
        <flux:text>Upload gambar (Max 1Mb)</flux:text>

        <input type="file" wire:model.live="form.attachments" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold
        file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
        @error('form.attachments')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        @error('form.attachments.*')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror

    </div>

    <!-- Progress Indicator -->
    <div x-show="uploading" class="space-y-2">
        <div class="flex justify-between text-sm text-gray-600">
            <span>Uploading...</span>
            <span x-text="progress + '%'"></span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div class="bg-blue-600 h-2.5 rounded-full" x-bind:style="'width: ' + progress + '%'"></div>
        </div>
    </div>

    {{-- @if ($form && $form->replyStates && isset($form->replyStates[$discussionId])) --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
        {{-- @foreach ($value as $index => $image)
        <div class="relative group rounded-md overflow-hidden border">
            <img src="{{ $image->temporaryUrl() }}" class="w-full h-32 object-cover" alt="Preview">
            <button type="button" wire:click="removeTemporaryImage('{{ $index }}')"
                class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity"
                title="Remove image">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>
            <div class="absolute bottom-0 left-0 right-0 bg-zinc-400 bg-opacity-100 text-white p-1 text-xs truncate">
                {{ $image->getClientOriginalName() }}
            </div>
        </div>
        @endforeach --}}
    </div>
    {{-- @endif --}}

</div>