<form x-data="{
    activeUploads: 0,
    progress: 0,
    get uploading() {
        return this.activeUploads > 0;
    }
    }" wire:submit="curation">

    {{-- <template x-for="part in {{ $this->publicRelations->documentUploads }}">
        <div class="space-y-2">
            <p x-text="part.part_number" class="mt-2"></p>
            <flux:input.file wire:model="curationFileUpload." />
        </div>
    </template> --}}

    <div x-on:livewire-upload-start="activeUploads++" x-on:livewire-upload-finish="activeUploads--"
        x-on:livewire-upload-error="activeUploads--" x-on:livewire-upload-cancel="activeUploads--"
        x-on:livewire-upload-progress="progress = $event.detail.progress" class="space-y-4">
        @foreach ($allowedDocument as $documentUpload)
            <div class="mt-4">
                <flux:subheading class="mb-2">{{ $documentUpload->part_number_label }}</flux:subheading>
                @php
                    $fieldName = 'curationFileUpload.' . $documentUpload->part_number;
                @endphp

                <flux:input.file wire:model="{{ $fieldName }}" />
                @error($fieldName)
                    <flux:text class="text-md text-red-500 mt-3">{{ $message }}</flux:text>
                @enderror
            </div>
        @endforeach
    </div>

    <div class="flex justify-end space-x-2">
        <flux:modal.close>
            <flux:button variant="subtle">{{ __('Batal') }}</flux:button>
        </flux:modal.close>

        <flux:button variant="primary" type="submit" x-bind:disabled="uploading">{{ __('Kurasi') }}</flux:button>
    </div>
</form>