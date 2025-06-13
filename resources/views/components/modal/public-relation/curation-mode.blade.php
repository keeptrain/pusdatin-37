<form wire:submit="curation">
    {{-- <template x-for="part in {{ $this->publicRelations->documentUploads }}">
        <div class="space-y-2">
            <p x-text="part.part_number" class="mt-2"></p>
            <flux:input.file wire:model="curationFileUpload." />
        </div>
    </template> --}}

    @foreach ($allowedDocument as $documentUpload)
        <p class="mt-2">{{ $documentUpload->part_number_label }}</p>
        @php
            $fieldName = 'curationFileUpload.' . $documentUpload->part_number;
        @endphp

        <flux:input.file wire:model="{{ $fieldName }}" />
        @error($fieldName)
        <flux:text class="text-md text-red-500 mt-3">{{ $message }}</flux:text>
        @enderror
    @endforeach

    <div class="flex justify-end space-x-2">
        <flux:modal.close>
            <flux:button  variant="ghost">{{ __('Batal') }}</flux:button>
        </flux:modal.close>

        <flux:button variant="primary" type="submit">{{ __('Kurasi') }}</flux:button>
    </div>
</form>
