<flux:modal name="process-completed-modal" focusable class="md:w-120" size="lg">
    <section>
        <flux:heading size="lg" x-text="mode === 'process' ? 'Proses permohonan' : 'Selesaikan permohonan'">
        </flux:heading>
        <flux:subheading
            x-text="mode === 'process' ? 'Permohonan ini siap untuk di proses' : 'Apakah Anda yakin ingin menyelesaikan permohonan ini?'">
        </flux:subheading>
    </section>

    <div class="flex justify-end space-x-2 mt-6">
        <!-- Tombol Batal -->
        <flux:modal.close>
            <flux:button variant="ghost" @click="mode = null">{{ __('Batal') }}</flux:button>
        </flux:modal.close>

        <!-- Tombol Proses/Selesaikan -->
        <template x-if="mode === 'process'">
            <flux:button wire:click="processPusdatin" variant="primary">{{ __('Proses') }}</flux:button>
        </template>
        <template x-if="mode === 'completed'">
            <flux:button wire:click="completed" variant="primary">{{ __('Selesaikan') }}</flux:button>
        </template>
    </div>
</flux:modal>