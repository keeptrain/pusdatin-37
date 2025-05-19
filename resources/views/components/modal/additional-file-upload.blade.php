<flux:modal name="upload-modal" focusable class="md:w-120" size="lg">
    <form wire:submit="additionalUploadFile" class="space-y-6">
        <flux:heading size="lg">
            {{ __('Upload file pendukung') }}
        </flux:heading>
        <flux:subheading size="lg">
            {{ __('File ini akan di upload dan di teruskan ke Pusdatin untuk permohonan dengan judul:') }}
            {{ $title }}
        </flux:subheading>
        <section>
            <flux:input.file wire:model="additionalFile"></flux:input.file>
            @error('additionalFile')
                <flux:text variant="strong" class="text-red-500 flex items-center mt-2 mr-2">
                    <flux:icon.exclamation-circle />{{ $message }}
                </flux:text>
            @enderror
        </section>
        <div class="flex justify-end space-x-2">
            <flux:modal.close>
                <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
            </flux:modal.close>

            <flux:button variant="primary" type="submit">{{ __('Teruskan') }}</flux:button>
        </div>
    </form>
</flux:modal>