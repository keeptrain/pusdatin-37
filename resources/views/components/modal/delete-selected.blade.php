@props([
    'selectedRequests' => null,
])
<flux:modal name="confirm-deletion" focusable class="w-100">
    <form wire:submit="deleteSelected" class="space-y-6">
        <flux:heading size="lg">{{ __('Konfirmasi Hapus Data ') }}</flux:heading>
        <div>
 <flux:legend>Total data yang di hapus <span x-text="selectedDataId.length"></span></flux:legend>
            <flux:subheading>{{ __('Apakah Anda yakin ingin melakukan ini?') }}</flux:subheading>
        </div>
        <div class="flex justify-end space-x-2">
            <flux:modal.close>
                <flux:button variant="subtle">{{ __('Batal') }}</flux:button>
            </flux:modal.close>

            <flux:button variant="danger" type="submit">{{ __('Ya, hapus') }}</flux:button>
        </div>
    </form>
</flux:modal>