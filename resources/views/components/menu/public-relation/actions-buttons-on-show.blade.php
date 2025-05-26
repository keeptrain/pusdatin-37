@can('queuePromkes', $publicRelationRequest)
    <flux:modal.trigger name="queue-promkes-modal" >
        <flux:button x-on:click="$dispatch('modal-show', { name: 'queue-promkes-modal' })" variant="primary" class="w-full" >
            {{ __('Konfirmasi Antrian') }}
        </flux:button>
    </flux:modal.trigger>
@elsecan('curationPromkes', $publicRelationRequest)
    <flux:modal.trigger name="curation-modal" >
        <flux:button x-on:click="$dispatch('modal-show', { name: 'curation-modal' })" variant="primary" icon="pencil-square" class="w-full" >
            {{ __('Kurasi') }}
        </flux:button>
    </flux:modal.trigger>
@elsecan('dispositionToPr', $publicRelationRequest)
    <flux:modal.trigger name="process-modal" >
            <flux:button x-on:click="$dispatch('modal-show', { name: 'process-modal' })" variant="primary" icon:trailing="arrow-right" class="w-full" >
                {{ __('Proses') }}
            </flux:button>
    </flux:modal.trigger>
@elsecan('completedPrProcess', $publicRelationRequest)
    <flux:modal.trigger name="completed-modal" >
        <flux:button x-on:click="$dispatch('modal-show', { name: 'completed-modal' })" variant="primary" class="w-full" >
            {{ __('Selesaikan') }}
        </flux:button>
    </flux:modal.trigger>
@endcan