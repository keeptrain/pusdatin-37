@php
    $files = $letter->uploads;
@endphp
<div class="overflow-x-auto">

    @if (session('status'))
        @php
            $variant = session('status')['variant'];
            $message = session('status')['message'];
        @endphp
        <flux:notification.toast :variant="$variant" :message="$message" :duration="3000" />
    @endif

    <flux:breadcrumbs>
        <flux:breadcrumbs.item :href="route('letter.table')" wire:navigate>Letter</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>{{ $letter->title }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <x-letters.detail-layout :letterId="$letterId">
        @if (!empty($files))


            <div x-data="{ activeTab: '{{ $files->first()->part_name }}' }" class="mt-6">
                <!-- Tabs -->
                <div class="flex space-x-2 border-b border-gray-200">
                    @foreach ($files as $file)
                        <button @click="activeTab = '{{ $file->part_name }}'"
                            :class="{ 'border-b-2 border-blue-500 text-blue-600': activeTab === '{{ $file->part_name }}' }"
                            class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-blue-600">
                            {{ ucfirst($file->part_name) }}
                        </button>
                    @endforeach
                </div>

                <!-- Tab Content -->
                <div class="mt-4">
                    @foreach ($files as $file)
                        <div x-show="activeTab === '{{ $file->part_name }}'" x-cloak>
                            <iframe src="{{ asset($file->file_path) }}" width="100%" height="600"
                                class="rounded shadow border">
                                This browser does not support PDFs. Please download the PDF to view it:
                                <a href="{{ asset($file->file_path) }}">Download PDF</a>
                            </iframe>
                        </div>
                    @endforeach
                </div>
            </div>
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
                    <flux:button variant="primary" wire:click="backStatus">
                        {{ __('Konfirmasi') }}
                    </flux:button>
                    <flux:modal.trigger name="confirm-letter-verification" wire:click="repliedLetter({{ $letterId }})">
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
                    <flux:button variant="primary">
                        {{ __('Back') }}
                    </flux:button>
                @break

                @default
            @endswitch
        </div>

        <livewire:letters.modal-confirmation />

        <x-slot name="rightPanel">

        </x-slot>
    </x-letters.detail-layout>

</div>
