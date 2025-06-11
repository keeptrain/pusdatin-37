<flux:modal name="process-pusdatin-modal" focusable class="md:w-120" size="lg">
    <section>
        <flux:heading size="lg">Proses permohonan</flux:heading>
        <flux:subheading>Permohonan ini siap untuk di proses</flux:subheading>
    </section>
    <div class="flex justify-end space-x-2 mt-6">
        <flux:modal.close>
            <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
        </flux:modal.close>

        <flux:button wire:click="processPusdatin" variant="primary">{{ __('Proses') }}</flux:button>
    </div>
</flux:modal>