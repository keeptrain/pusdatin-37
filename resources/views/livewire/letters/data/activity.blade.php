<div>
    <flux:button :href="route('letter.table')" icon="arrow-long-left" variant="subtle">Back to Table</flux:button>

    <x-letters.detail-layout :letterId="$letterId">
        <div class="p-12">
            <flux:notification.status-stepped :status="$letter->status->label()" />

            @if ($letter->active_revision)
                <flux:button wire:click="detailPage({{ $letterId }})" class="mt-6" wire:navigate> Revisi
                </flux:button>
            @endif

            @foreach ($activity as $date => $hours)
                <!-- Activity letter -->
                <div class="border border-gray-200 rounded-lg mt-4 md:mt-8">
                    <div class="p-4 flex items-center">
                        <flux:icon.calendar />
                        <flux:heading class="ml-3">{{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                        </flux:heading>
                    </div>

                    @foreach ($hours as $hour => $actions)
                        <div class="border-t border-gray-200">
                            <div class="flex flex-col md:flex-row md:justify-between md:items-center p-4">

                                <div class="mb-3 md:mb-0">
                                    @foreach ($actions as $action)
                                        <flux:subheading>
                                            {{ $action->action }}
                                        </flux:subheading>
                                        @if (!empty($action->notes))
                                            Notes: {{ $action->notes }}
                                        @endif
                                    @endforeach
                                </div>
                                <div class="flex flex-col md:flex-row md:items-center md:space-x-4">
                                    <div class="mb-2 md:mb-0">

                                        {{ $hour }}
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </x-letters.detail-layout>

</div>
