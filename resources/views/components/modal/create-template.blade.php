<flux:modal name="create-template" focusable class="md:w-120" size="lg">
    <form wire:submit="save" class="space-y-6">
        <div>
            <flux:heading size="lg">
                {{ __('Buat template') }}
            </flux:heading>
        </div>
        <flux:input wire:model="name" name="name" label="Nama template" />
        <flux:select wire:model="partNumber" label="Bagian template" placeholder="Pilih bagian...">
            <flux:select.option value="1">SPBE</flux:select.option>
            <flux:select.option value="2">SOP</flux:select.option>
            <flux:select.option value="3">Pemanfaatan Aplikasi</flux:select.option>
            <flux:select.option value="4">RFC</flux:select.option>
            <flux:select.option value="5">NDA</flux:select.option>
        </flux:select>
        <flux:input wire:model="file" type="file" name="file" label="File template" />
        <div class="flex justify-end space-x-2">
            <flux:modal.close>
                <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
            </flux:modal.close>

            <flux:button variant="primary" type="submit">{{ __('Buat') }}</flux:button>
        </div>
    </form>
</flux:modal>