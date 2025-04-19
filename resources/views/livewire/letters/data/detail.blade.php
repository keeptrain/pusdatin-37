<div class="overflow-x-auto">

    @if (session('status'))
        @php
            $variant = session('status')['variant'];
            $message = session('status')['message'];
        @endphp
        <flux:notification.toast :variant="$variant" :message="$message" :duration="3000" />
    @endif

    <flux:breadcrumbs>
        {{-- <flux:breadcrumbs.item :href="route('dashboard')" wire:navigate icon="home" /> --}}
        <flux:breadcrumbs.item :href="route('letter.table')" wire:navigate>Letter</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>{{ $letter->title }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <x-letters.detail-layout :letterId="$letterId">

        @if ($letter->letterable->file_name != null)
            {{ $letter->letterable->file_name  }}
            <iframe src="{{ asset($letter->letterable->file_path) }}" width="100%" height="600" class="mt-6" lazy>
                This browser does not support PDFs. Please download the PDF to view it: Download PDF
            </iframe>
        @endif

        {{ $letter->letterable->body }}

        <div class="flex justify-end mt-6">
            @switch($letter->status->label())
                @case(Pending::class)
                    <flux:button variant="primary" wire:click="processLetter({{ $letter->id }})">
                        {{ __('Process') }}
                    </flux:button>
                @break

                @case(Process::class)
                    <flux:modal.trigger name="confirm-letter-verification" wire:click="repliedLetter({{ $letterId }})">
                        <flux:button variant="primary" type="click">
                            {{ __('Verifikasi') }}
                        </flux:button>
                    </flux:modal.trigger>
                @break

                @case(Replied::class)
                    <flux:modal.trigger name="confirm-letter-process" wire:click="backStatus">
                        <flux:button variant="primary">
                            {{ __('Konfirmasi') }}
                        </flux:button>
                    </flux:modal.trigger>
                    <flux:modal.trigger name="confirm-letter-process">
                        <flux:button variant="primary">
                            {{ __('Konfirmasi') }}
                        </flux:button>
                    </flux:modal.trigger>
                @break

                @case(Approved::class)
                    <flux:modal.trigger name="confirm-letter-process" wire:click="backStatus">
                        <flux:button variant="primary">
                            {{ __('Back') }}
                        </flux:button>
                    </flux:modal.trigger>
                @break

                @case(Rejected::class)
                    <flux:modal.trigger name="confirm-letter-process" wire:click="backStatus">
                        <flux:button variant="primary">
                            {{ __('Back') }}
                        </flux:button>
                    </flux:modal.trigger>
                @break

                @default
            @endswitch
        </div>

        <livewire:letters.modal-confirmation />


        <x-slot name="rightPanel">

        </x-slot>
    </x-letters.detail-layout>

</div>
