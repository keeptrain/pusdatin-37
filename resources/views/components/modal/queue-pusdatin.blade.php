<flux:modal name="queue-pusdatin-modal" focusable class="md:w-120" size="lg">
    <section>
        <flux:heading size="lg">Antrikan permohonan</flux:heading>
        <flux:subheading>Permohonan ini akan di antrikan di Kehumasan</flux:subheading>
    </section>
    <div class="flex justify-end space-x-2 mt-6">
        <flux:modal.close>
            <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
        </flux:modal.close>

        <flux:button wire:click="queuePusdatin" variant="primary">{{ __('Ya Antrikan') }}</flux:button>
    </div>
</flux:modal>