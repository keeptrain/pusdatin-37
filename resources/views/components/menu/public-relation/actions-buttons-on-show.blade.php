<flux:modal.trigger name="action-on-show-modal">
@can('queuePromkes', $publicRelationRequest)
        <flux:button @click="mode ='queue_promkes'" variant="primary" class="w-full">
            {{ __('Konfirmasi Antrian') }}
        </flux:button>
@elsecan('curationPromkes', $publicRelationRequest)
        <flux:button @click="mode = 'curation'" variant="primary" icon="pencil-square" class="w-full">
            {{ __('Kurasi') }}
        </flux:button>
@elsecan('queuePusdatin', $publicRelationRequest)
        <flux:button @click="mode = 'queue_pusdatin'" variant="primary" icon:trailing="arrow-right"  class="w-full">
            {{ __('Antrean Pusdatin') }}
        </flux:button>
@elsecan('processPusdatin', $publicRelationRequest)
        <flux:button @click="mode = 'process_pusdatin'" variant="primary" icon:trailing="arrow-right" class="w-full">
            {{ __('Proses') }}
        </flux:button>
@elsecan('completedRequest', $publicRelationRequest)
        <flux:button @click="mode = 'completed'" variant="primary" class="w-full">
            {{ __('Selesaikan') }}
        </flux:button>
@endcan
</flux:modal.trigger>
