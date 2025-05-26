@can('viewDisposition', $letter)
    <flux:modal.trigger name="disposition-modal">
        <flux:button x-on:click="$dispatch('modal-show', { name: 'disposition-modal' })" variant="primary" icon:trailing="arrow-right" class="w-full">Disposisi</flux:button>
    </flux:modal.trigger>
@elsecan('viewVerificationSiStep1', $letter)
    <flux:modal.trigger name="verification-modal" x-on:click="$dispatch('modal-show', { name: 'verification-modal' })">
        <flux:button variant="primary" type="click" icon="check" class="w-full" :disabled="$letter->active_revision == true">
            {{ __('Verifikasi') }}
        </flux:button>
    </flux:modal.trigger>
@elsecan('viewVerificationDataStep1', $letter)
    <flux:modal.trigger name="verification-modal" x-on:click="$dispatch('modal-show', { name: 'verification-modal' })">
        <flux:button variant="primary" type="click" icon="check" class="w-full" :disabled="$letter->active_revision == true">
            {{ __('Verifikasi') }}
        </flux:button>
    </flux:modal.trigger>
@elsecan('viewReviewSiStep1', $letter)
    <flux:button href="{{ route('letter.review', [ $letterId ]) }}" variant="primary" type="click" icon="viewfinder-circle" class="w-full" :disabled="$letter->active_revision == true" wire:navigate>
        {{ __('Review') }}
    </flux:button>
@elsecan('viewReviewDataStep1', $letter)
    <flux:button href="{{ route('letter.review', [ $letterId ]) }}" variant="primary" type="click" icon="viewfinder-circle" class="w-full" :disabled="$letter->active_revision == true" wire:navigate>
        {{ __('Review') }}
    </flux:button>
@elsecan('viewVerificationStep2', $letter)
    <flux:modal.trigger name="approved-modal">
        <flux:button x-on:click="$dispatch('modal-show', { name: 'approved-modal' })" variant="primary" icon="check-badge" class="w-full" :disabled="$letter->active_revision == true">Verifikasi</flux:button>
    </flux:modal.trigger>
@elsecan('viewReviewStep2', $letter)
    <flux:button href="{{ route('letter.review', [ $letterId ]) }}" variant="primary" type="click" icon="viewfinder-circle" class="w-full" :disabled="$letter->active_revision == true" wire:navigate>
        {{ __('Review') }}
    </flux:button>
@endcan
