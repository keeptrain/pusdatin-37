<div>
    <flux:breadcrumbs>
        <flux:breadcrumbs.item :href="route('letter.table')" wire:navigate>Letter</flux:breadcrumbs.item>
        <flux:breadcrumbs.item >{{ $letter->title }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>


    <x-letters.detail-layout :letterId="$letterId">
        <form wire:submit="save" class="space-y-6 mb-6">
            <div class="grid grid-cols-2 gap-x-4">
    
                <div>
                    <flux:input wire:model="responsible_person" label="Penanggung jawab" placeholder="Nama lengkap"
                        value="{{ $letter->responsible_person }}"
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
    
            <flux:input wire:model="reference_number" class="max-w-sm" label="Nomor Surat" placeholder="No/xx/2025"
                value="{{ $letter->reference_number }}"
                clearable />

                <flux:input wire:model="file" type="file" label="Upload" badge="Required" class="max-w-sm" />
    
            </div>
    
            <div class="flex flex-row justify-start">
                <flux:button type="submit" variant="primary" disabled>{{ __('Update') }}</flux:button>
            </div>
        </form>

    </x-letters.detail-layout>
</div>
