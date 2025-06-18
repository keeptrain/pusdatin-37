<form wire:submit="completed">
    <div>
        @foreach ($allowedDocument as $upload)
            @php
                $fieldName = 'mediaLinks.' . $upload->part_number;
            @endphp
            <section class="mt-6 mb-6">
                <p class="mb-2">{{ $upload->part_number_label }}</p>
                <flux:textarea wire:model="{{ $fieldName }}" placeholder="Masukkan link disini..." rows="2" />
                @error($fieldName)
                    <flux:text class="text-md text-red-500">{{ $message }}</flux:text>
                @enderror
            </section>
        @endforeach
    </div>

    <div class="flex justify-end space-x-2">
        <flux:modal.close>
            <flux:button variant="subtle">{{ __('Batal') }}</flux:button>
        </flux:modal.close>

        <flux:button variant="primary" type="submit">{{ __('Kirim') }}</flux:button>
    </div>
</form>