<x-letters.layout legend="Direct Form">

    <form wire:submit="save" class="space-y-6">
        <div class="grid grid-cols-2 gap-x-4">
            <div>
                <flux:input wire:model="responsible_person" label="Penanggung jawab" placeholder="Nama" clearable />
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

        <flux:input wire:model="reference_number" class="max-w-sm" label="Nomor Surat" placeholder="No/xx/2025"
            clearable />

        <flux:textarea wire:model="body" label="Latar belakang masalah" rows="3" />

        {{-- <flux:textarea label="2. Analisis manfaat" rows="auto" />

        <flux:textarea label="3.1 Analisis kelayakan waktu" rows="auto" />

        <flux:textarea label="3.2 Analisis kelayakan lingkungan pendukung" rows="auto" />

        <flux:textarea label="4.1 Deskripsi singkat" rows="auto" />

        <flux:textarea label="4.2 Persyaratan Khusus (Specific Requirement)" rows="auto" />

        <flux:textarea label="4.3 Batasan Sistem (Constraint Requirement)" rows="auto" /> --}}

        <div class="flex flex-row justify-between">
            <flux:button type="button" :href="route('letter')" wire:navigate>{{ __('Cancel') }}</flux:button>

            <div class="flex">
                <flux:dropdown class="">
                    <flux:button icon="ellipsis-horizontal" class="mr-2" />
                    <flux:menu>
                        <flux:menu.radio.group>
                            <flux:menu.group heading="Save as">
                                <flux:menu.item wire:click="filter('draft')">{{ __('Draft') }}</flux:menu.item>
                                <flux:menu.item wire:click="filter('published')">{{ __('PDF') }}</flux:menu.item>
                            </flux:menu.group>
                        </flux:menu.radio.group>
                    </flux:menu>
                </flux:dropdown>
            <flux:button variant="primary" type="submit">{{ __('Next') }}</flux:button>
            </div>
        </div>
    </form>
    
</x-letters.layout>
