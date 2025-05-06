<div class="overflow-x-auto">
    @if (session('status'))
        @php
            $variant = session('status')['variant'];
            $message = session('status')['message'];
        @endphp
        <flux:notification.toast :variant="$variant" :message="$message" :duration="3000" />
    @endif

    <flux:button :href="route('letter.table')" icon="arrow-long-left" variant="subtle">Back to Table</flux:button>

    <div x-data="{ partTab: '{{ $uploads->first()->part_name ?? '' }}' }">
        <x-letters.detail-layout :letterId="$letterId">

            @if (!empty($uploads))
                <!-- Tab Content -->
                <div class="mt-3 mr-3">
                    @foreach ($uploads as $file)
                        <div x-show="partTab === '{{ $file->part_name }}'" x-cloak>
                            <iframe loading="lazy" src="{{ asset($file->file_path) }}" width="100%" height="800"
                                class="rounded shadow border-none">
                                This browser does not support PDFs. Please download the PDF to view it:
                                <a href="{{ asset($file->file_path) }}">Download PDF</a>
                            </iframe>
                        </div>
                    @endforeach

                    @if ($showModal)
                        <livewire:letters.modal-confirmation :letterId="$letterId" :activeRevision="$activeRevision" />
                    @endif
                </div>
            @endif

            @foreach ($directs as $item)
                {{ $item->body }}
            @endforeach

            <x-slot name="rightSidebar">
                <h3 class="text-lg font-bold mb-4">General</h3>

                <div class="mb-6">
                    <h4 class="text-gray-500 mb-1">Title</h4>
                    <p class="text-gray-800">
                        {{ $letter->title }}
                    </p>
                </div>

                <div class="mb-6">
                    <h4 class="text-gray-500 mb-1">Responsible person</h4>
                    <p class="text-gray-800">
                        {{ $letter->responsible_person }}
                    </p>
                </div>

                <div class="mb-6">
                    <h4 class="text-gray-500 mb-1">Reference number</h4>
                    <p class="text-gray-800">
                        {{ $letter->reference_number }}
                    </p>
                </div>

                <div class="mb-6">
                    <h4 class="text-gray-500 mb-1">Created at</h4>
                    <p class="text-gray-800">{{ $letter->createdAtWithTime() }}</p>
                </div>

                <div class="mb-6">
                    <h4 class="text-gray-500 mb-1">Updated at</h4>
                    <p class="text-gray-800">{{ $letter->updated_at }}</p>
                </div>

                <div class="mb-6">
                    <h4 class="text-gray-500 mb-1">Status</h4>
                    <flux:notification.status-badge status="{{ $letter->status->label() }}">
                        {{ $letter->status->label() }}</flux:notification.status-badge>
                </div>

                <div class="border-1 p-3">
                    <h4 class="text-gray-500 mb-3">Documents</h4>
                    <div class="space-y-3">
                        @foreach ($uploads as $file)
                            <div class="flex items-center">
                                <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center mr-2">
                                  <flux:icon.document-magnifying-glass class="size-4"/>
                                </div>
                                <button @click="partTab = '{{ $file->part_name }}'" class="text-gray-800 cursor-pointer"
                                    :class="{'border-b-2 border-blue-500 text-blue-600': partTab === '{{ $file->part_name }}' }">{{ ucfirst($file->part_name) }}</button>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="flex flex-1 gap-2 mt-6">
                    @switch($letter->status->label())
                        @case('Pending')
                            <flux:button variant="primary" wire:click="processLetter({{ $letter->id }})" class="w-full">
                                {{ __('Process') }}
                            </flux:button>
                        @break

                        @default
                        <flux:modal.trigger name="confirm-letter-verification"
                            wire:click="repliedLetter({{ $letterId }})">
                            <flux:button variant="primary" type="click" class="w-full" :disabled="$letter->active_revision == 1">
                                {{ __('Verifikasi') }}
                            </flux:button>
                        </flux:modal.trigger>
                    @endswitch
                    <flux:dropdown>
                        <flux:button icon="ellipsis-horizontal"/>
                        <flux:menu>
                            <flux:modal.trigger name="confirm-letter-verification" wire:click="repliedLetter({{ $letterId }})">
                                <flux:menu.item :href="route('letter.edit', [$letterId])" icon="pencil-square" >Force edit</flux:menu.item>
                            </flux:modal.trigger>
                            <flux:menu.item  :href="route('letter.rollback', [$letterId])" icon="arrow-path" >Rollback</flux:menu.item>
                            <flux:menu.item icon="trash" variant="danger" >Delete</flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </x-slot>
        </x-letters.detail-layout>
    </div>
</div>
