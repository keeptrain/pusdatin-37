@can('queuePromkes', $publicRelationRequest)
    <flux:modal.trigger name="queue-promkes-modal">
        <flux:button variant="primary" class="w-full">
            {{ __('Konfirmasi Antrian') }}
        </flux:button>
    </flux:modal.trigger>
@elsecan('curationPromkes', $publicRelationRequest)
    <flux:modal.trigger name="curation-modal">
        <flux:button variant="primary" icon="pencil-square" class="w-full">
            {{ __('Kurasi') }}
        </flux:button>
    </flux:modal.trigger>
@elsecan('queuePusdatin', $publicRelationRequest)
    <flux:modal.trigger name="queue-pusdatin-modal">
        <flux:button variant="primary" icon:trailing="arrow-right" class="w-full">
            {{ __('Antrean Pusdatin') }}
        </flux:button>
    </flux:modal.trigger>
@elsecan('processPusdatin', $publicRelationRequest)
    <flux:modal.trigger name="process-pusdatin-modal">
        <flux:button variant="primary" icon:trailing="arrow-right" class="w-full">
            {{ __('Proses') }}
        </flux:button>
    </flux:modal.trigger>
@elsecan('completedRequest', $publicRelationRequest)
    <flux:modal.trigger name="completed-modal">
        <flux:button variant="primary" class="w-full">
            {{ __('Selesaikan') }}
        </flux:button>
    </flux:modal.trigger>
@endcan