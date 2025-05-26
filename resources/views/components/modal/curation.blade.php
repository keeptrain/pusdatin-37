<flux:modal name="curation-modal" focusable class="md:w-120" size="lg">
    <flux:heading size="lg">Kurasi materi </flux:heading>
    <form wire:submit="curation">
        {{-- <template x-for="part in {{ $this->publicRelations->documentUploads }}">
            <div class="space-y-2">
                <p x-text="part.part_number" class="mt-2"></p>
                <flux:input.file wire:model="curationFileUpload." />
            </div>
        </template> --}}

        @foreach ($publicRelationRequest->documentUploads as $documentUpload)
            <p class="mt-2">{{ $documentUpload->part_number_label}}</p>
            <flux:input.file wire:model="curationFileUpload.{{ $documentUpload->part_number }}" />
        @endforeach

        @error('curationFileUpload')
            <flux:text class="text-md text-red-500">{{ $message }}</flux:text>
        @enderror

        <div class="flex justify-end space-x-2">
            <flux:modal.close>
                <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
            </flux:modal.close>

            <flux:button variant="primary" type="submit">{{ __('Kurasi') }}</flux:button>
        </div>
    </form>
</flux:modal>