<flux:modal name="action-on-show-modal" focusable class="md:w-120" size="lg">
    <section>
        <flux:heading size="lg" x-text="mode === 'process_pusdatin' ? 'Permohonan ini siap untuk di proses' : (mode === 'curation' ? 'Kurasi materi' : (mode === 'completed' ? 'Selesaikan permohonan' : 'Antrikan permohonan'))"></flux:heading>
        <flux:subheading x-text="mode === 'queue_promkes' ? 'Permohonan ini akan di antrikan' : (mode === 'queue_pusdatin' ? 'Permohonan ini akan di antrikan di Pusdatin' : (mode === 'completed' ? 'Masukkan link sesuai dengan jenis media yang di ajukan' : (mode === 'curation' ? '' : 'Permohonan ini siap untuk di proses')))"></flux:subheading>
    </section>

    <template x-if="mode === 'curation'">
        <x-modal.public-relation.curation-mode :allowedDocument="$this->getAllowedDocument" />
    </template>

    <template x-if="mode === 'completed'">
        <x-modal.public-relation.completed-mode :allowedDocument="$this->getAllowedDocument" />
    </template>

    <template x-if="mode !== 'curation' && mode !== 'completed'">
        <div class="flex justify-end space-x-2 mt-6">
            <flux:modal.close>
                <flux:button @click="mode = null" variant="ghost">{{ __('Batal') }}</flux:button>
            </flux:modal.close>

            <template x-if="mode === 'queue_promkes'">
                <flux:button wire:click="queuePromkes" variant="primary">{{ __('Ya Antrikan') }}</flux:button>
            </template>

            <template x-if="mode === 'queue_pusdatin'">
                <flux:button wire:click="queuePusdatin" variant="primary">{{ __('Ya Antrikan') }}</flux:button>
            </template>

            <template x-if="mode === 'process_pusdatin'">
                <flux:button wire:click="processPusdatin" variant="primary">{{ __('Ya Antrikan') }}</flux:button>
            </template>
        </div>
    </template>
</flux:modal>
