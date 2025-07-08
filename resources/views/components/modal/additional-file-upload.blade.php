<flux:modal name="upload-modal" focusable class="md:w-120" size="lg">
    <form x-data="uploading" wire:submit="additionalUploadFile" class="space-y-6">
        <flux:heading size="lg">
            {{ __('Upload file pendukung') }}
        </flux:heading>
        <flux:subheading size="lg">
            {{ __('File ini akan di upload dan di teruskan ke Pusdatin.') }}
            {{-- {{ $title }} --}}
        </flux:subheading>
        <section x-on:livewire-upload-start="activeUploads++" x-on:livewire-upload-finish="activeUploads--"
            x-on:livewire-upload-error="activeUploads--" x-on:livewire-upload-cancel="activeUploads--"
            x-on:livewire-upload-progress="progress = $event.detail.progress">
            <flux:input.file wire:model="additionalFile"></flux:input.file><!-- Progress Bar -->
            <template x-if="uploading">
                <div class="mt-3">
                    <div class="w-full bg-gray-200 rounded-md h-2.5">
                        <div class="bg-blue-300 h-2.5 rounded-md transition-all duration-200" :style="'width: ' + progress + '%'"></div>
                    </div>
                    <div class="text-xs text-gray-500 mt-1" x-text="progress + '%'"></div>
                </div>
            </template>
            @error('additionalFile')
                <flux:text variant="strong" class="text-red-500 flex items-center mt-2 mr-2">
                    <flux:icon.exclamation-circle />{{ $message }}
                </flux:text>
            @enderror
        </section>
        <div class="flex justify-end space-x-2">
            <flux:modal.close>
                <flux:button variant="subtle">{{ __('Cancel') }}</flux:button>
            </flux:modal.close>

            <flux:button variant="primary" type="submit" x-bind:disabled="uploading">{{ __('Kirim') }}</flux:button>
        </div>
    </form>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('uploading', () => ({
                additionalFile: null,
                activeUploads: 0,
                progress: 0,
                get uploading() {
                    return this.activeUploads > 0;
                }
            }))
        })
    </script>
</flux:modal>