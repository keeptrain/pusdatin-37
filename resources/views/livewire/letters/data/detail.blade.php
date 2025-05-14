@php
    $status = $letter->status->label()
@endphp

<div class="overflow-x-auto">
    <flux:button :href="route('letter.table')" icon="arrow-long-left" variant="subtle">Back to Table</flux:button>

    <div x-data="{ partTab: '{{ $uploads->first()->part_number ?? '' }}' }">
        <x-letters.detail-layout :letterId="$letterId">
            @if (!empty($uploads))
                <!-- Tab Content -->
                <div class="mt-3 mr-3">
                    @foreach ($uploads as $file)
                        <div x-show="partTab === '{{ $file->part_number }}'" x-cloak>
                            <iframe loading="lazy" src="{{ asset($file->file_path) }}" width="100%" height="800"
                                class="rounded shadow border-none">
                                This browser does not support PDFs. Please download the PDF to view it:
                                <a href="{{ asset($file->file_path) }}">Download PDF</a>
                            </iframe>
                        </div>
                    @endforeach
                    
                    <livewire:letters.modal-confirmation :letterId="$letterId" />
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
                    <flux:notification.status-badge status="{{ $status }}">
                        {{ $status }}</flux:notification.status-badge>
                </div>

                <div class="border-1 p-3">
                    <h4 class="text-gray-500 mb-3">Documents</h4>
                    <div class="space-y-3">
                        @foreach ($uploads as $file)
                            <div class="flex items-center">
                                <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center mr-2">
                                  <flux:icon.document-magnifying-glass class="size-4"/>
                                </div>
                                <button @click="partTab = '{{ $file->part_number }}'" class="text-gray-800 cursor-pointer"
                                    :class="{'border-b-2 border-blue-500 text-blue-600': partTab === '{{ $file->part_number }}' }">{{ $file->part_number }}</button>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="flex flex-1 gap-2 mt-6">
                    @switch($status)
                        @case('Process')
                        @case('Replied')
                        <flux:modal.trigger name="verification-modal" x-on:click="$dispatch('modal-show', { name: 'verification-modal' })">
                            <flux:button variant="primary" type="click" icon="check-badge" class="w-full" :disabled="$letter->active_revision == 1">
                                {{ __('Verifikasi') }}
                            </flux:button>
                        </flux:modal.trigger>

                        <flux:dropdown>
                            <flux:button icon="ellipsis-horizontal"/>
                            <flux:menu>
                                <flux:menu.item :href="route('letter.edit', [$letterId])" icon="pencil-square">Force edit</flux:menu.item>
                                <flux:menu.item :href="route('letter.rollback', [$letterId])" icon="backward">Rollback</flux:menu.item>
                                <flux:menu.item icon="trash" variant="danger">Delete</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                        @break
                        @case('Pending')
                            <flux:modal.trigger name="disposition-modal">
                                <flux:button x-on:click="$dispatch('modal-show', { name: 'disposition-modal' })" variant="primary" icon:trailing="arrow-right" class="w-full">Disposisi</flux:button>
                            </flux:modal.trigger>
                        @break
                        @case('Approved by Kasatpel')  
                            <flux:modal.trigger name="approved-modal">
                                <flux:button x-on:click="$dispatch('modal-show', { name: 'approved-modal' })" variant="primary" icon:trailing="check" class="w-full">Approved</flux:button>
                            </flux:modal.trigger>
                        @break
                        @default
                            <flux:dropdown class="w-full">
                                <flux:button icon="ellipsis-horizontal" class="w-full"/>
                                <flux:menu>
                                    <flux:menu.item :href="route('letter.edit', [$letterId])" icon="pencil-square">Force edit</flux:menu.item>
                                    <flux:menu.item :href="route('letter.rollback', [$letterId])" icon="backward">Rollback</flux:menu.item>
                                    <flux:menu.item icon="trash" variant="danger">Delete</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                    @endswitch
                </div>
            </x-slot>
        </x-letters.detail-layout>
    </div>
</div>
