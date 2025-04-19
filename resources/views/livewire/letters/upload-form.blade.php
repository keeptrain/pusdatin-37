<x-letters.layout legend="Direct Form">

    <form wire:submit="save" class="space-y-6 mb-6">
        <div class="grid grid-cols-2 gap-x-4">

            <div>
                <flux:input wire:model="responsible_person" label="Penanggung jawab" placeholder="Nama lengkap"
                    clearable />
            </div>

            <div>
                <flux:select wire:model="section" label="Section" placeholder="Choose section...">
                    <flux:select.option>Seksi A</flux:select.option>
                    <flux:select.option>Seksi B</flux:select.option>
                    <flux:select.option>Seksi C</flux:select.option>
                    <flux:select.option>Seksi D</flux:select.option>
                </flux:select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-x-4">
            <flux:input wire:model="title" label="Judul" placeholder="Judul permohonan layanan" clearable />

            <flux:input wire:model="reference_number" class="max-w-sm" label="Nomor Surat" placeholder="No/xx/2025"
                clearable />
        </div>

        <flux:input wire:model="file" type="file" label="Upload" badge="Required" class="max-w-sm" />

        <flux:description>Maximum size 3MB.</flux:description>

        </div>

        <div class="flex flex-row justify-between">
            <flux:button type="button" :href="route('letter')" wire:navigate>{{ __('Cancel') }}</flux:button>
            <flux:button type="submit" variant="primary">{{ __('Upload') }}</flux:button>
        </div>
    </form>
    
</x-letters.layout>
