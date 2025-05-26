<flux:modal name="queue-promkes-modal" focusable class="md:w-120" size="lg">
    <section>
        <flux:heading size="lg">Antrian permohonan</flux:heading>
        <flux:subheading>Permohonan ini akan di antrikan</flux:subheading>
    </section>
    <div class="flex justify-end space-x-2">
        <flux:modal.close>
            <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
        </flux:modal.close>

        <flux:button wire:click="queuePromkes" variant="primary">{{ __('Ya') }}</flux:button>
    </div>
</flux:modal>