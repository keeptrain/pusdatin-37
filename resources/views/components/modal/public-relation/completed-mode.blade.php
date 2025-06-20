<form wire:submit="completed">
    <div>
        @foreach ($allowedDocument as $upload)
            <section class="mt-6 mb-6">
                <p class="mb-3">{{ $upload->part_number_label }}</p>
                <flux:textarea wire:model="mediaLinks.{{ $upload->part_number }}" placeholder="Masukkan link disini..."
                               rows="2" />
            </section>
        @endforeach

        @error('mediaLinks')
        <flux:text class="text-md text-red-500">{{ $message }}</flux:text>
        @enderror
    </div>

    <div class="flex justify-end space-x-2">
        <flux:modal.close>
            <flux:button  variant="ghost">{{ __('Batal') }}</flux:button>
        </flux:modal.close>

        <flux:button variant="primary" type="submit">{{ __('Kirim') }}</flux:button>
    </div>
</form>
