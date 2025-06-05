<flux:modal name="edit-template" focusable class="md:w-120" size="lg">
    <form wire:submit="update" class="space-y-6">
        <div>
            <flux:heading size="lg">
                {{ __('Edit template') }}
            </flux:heading>
        </div>
        <flux:input wire:model="updateName" label="Nama" />
        <flux:radio.group wire:model="updateIsActive" label="Kondisi template">
            <flux:radio name="is_active" value="true" label="Aktif"
                description="Bisa di download oleh pemohon pada form." checked />
            <flux:radio name="is_active" value="false" label="Tidak aktif"
                description="Tidak bisa di download oleh pemohon pada form." />
        </flux:radio.group>
        <flux:input wire:model="file" type="file" name="file" label="File template" />

        <div class="flex justify-end space-x-2">
            <flux:modal.close>
                <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
            </flux:modal.close>

            <flux:button variant="primary" type="submit">{{ __(key: 'Ubah') }}</flux:button>
        </div>
    </form>
</flux:modal>˝