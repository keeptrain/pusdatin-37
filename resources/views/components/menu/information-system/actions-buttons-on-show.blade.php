@can('viewDisposition', $systemRequest)
    <flux:modal.trigger name="disposition-modal">
        <flux:button variant="primary" icon:trailing="arrow-right" class="w-full">Disposisi</flux:button>
    </flux:modal.trigger>
@elsecan('viewVerificationSiStep1', $systemRequest)
    <flux:modal.trigger name="verification-modal">
        <flux:button variant="primary" type="click" icon="check" class="w-full">
            {{ __('Verifikasi') }}
        </flux:button>
    </flux:modal.trigger>
@elsecan('viewVerificationDataStep1', $systemRequest)
    <flux:modal.trigger name="verification-modal">
        <flux:button variant="primary" type="click" icon="check" class="w-full">
            {{ __('Verifikasi') }}
        </flux:button>
    </flux:modal.trigger>
@elsecan('viewReviewSiStep1', $systemRequest)
    <flux:button href="{{ route('is.review', [$systemRequestId]) }}" variant="primary" type="click"
        icon="viewfinder-circle" class="w-full" wire:navigate>
        {{ __('Review') }}
    </flux:button>
@elsecan('viewReviewDataStep1', $systemRequest)
    <flux:button href="{{ route('is.review', [$systemRequestId]) }}" variant="primary" type="click"
        icon="viewfinder-circle" class="w-full" wire:navigate>
        {{ __('Review') }}
    </flux:button>
@elsecan('viewVerificationStep2', $systemRequest)
    <flux:modal.trigger name="verification-modal">
        <flux:button variant="primary" icon="check-badge" class="w-full">
            Verifikasi</flux:button>
    </flux:modal.trigger>
@elsecan('viewReviewStep2', $systemRequest)
    <flux:button href="{{ route('is.review', [$systemRequestId]) }}" variant="primary" type="click"
        icon="viewfinder-circle" class="w-full" wire:navigate>
        {{ __('Review') }}
    </flux:button>
@elsecan('actionProcessRequest', $systemRequest)
    <flux:modal.trigger name="process-completed-modal">
        <flux:button x-on:click="mode = 'process'" variant="primary" icon="rocket-launch" class="w-full">Proses
        </flux:button>
    </flux:modal.trigger>
@elsecan('actionCompletedRequest', $systemRequest)
    <flux:button x-on:click="mode = 'completed'; $dispatch('modal-show', { name: 'process-completed-modal' });"
        variant="primary" icon="rocket-launch" class="w-full">Selesaikan</flux:button>
@endcan