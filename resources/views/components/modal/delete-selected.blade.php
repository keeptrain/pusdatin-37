<flux:modal name="confirm-deletion" focusable class="max-w-lg">
    <form wire:submit="deleteSelected" class="space-y-6">
        <div>
            <flux:heading size="lg">{{ __('Menghapus data yang dipilih ') }}</flux:heading>

            <flux:subheading>
                {{ __('Apakah Anda yakin ingin melakukan ini?') }}
            </flux:subheading>
        </div>
        <div class="flex justify-end space-x-2">
            <flux:modal.close>
                <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
            </flux:modal.close>

            <flux:button variant="danger" type="submit">{{ __('Confirm') }}</flux:button>
        </div>
    </form>
</flux:modal>