<div class="bg-zinc-50 border rounded-lg p-4">
    <div class="flex items-center justify-between mb-4">
        <flux:text size="sm" class="font-medium text-gray-500 uppercase">{{ $meeting['status']}}</flux:text>
        <flux:dropdown>
            <flux:button variant="ghost" icon="ellipsis-vertical" />
            <flux:menu>
                @if (isset($meeting['result']))
                    <flux:modal.trigger name="edit-meeting-{{ $key }}-modal">
                        <flux:menu.item icon="pencil-square" class="w-full">Edit</flux:menu.item>
                    </flux:modal.trigger>
                @endif
                <flux:menu.item wire:click="delete({{ $key }})" icon="trash" variant="danger">Delete
                </flux:menu.item>
            </flux:menu>
        </flux:dropdown>
    </div>

    <div class="flex flex-col md:flex-row gap-4">
        <!-- Left column -->
        <div class="flex-1 space-y-3 min-h-[100px]">
            <div class="flex items-start text-sm space-x-2">
                @if (!empty($meeting['location']))
                    <div class="flex-shrink-0 mt-1">
                        <flux:icon.map-pin class="size-5 text-gray-400" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <flux:subheading size="lg" class="text-gray-500">Lokasi:
                            <span class="text-gray-800">{{ $meeting['location'] }}</span>
                        </flux:subheading>
                    </div>
                @elseif (!empty($meeting['link']))
                    <div class="flex-shrink-0 mt-1">
                        <flux:icon.video-camera class="size-5 text-gray-400" />
                    </div>
                    <div class="flex flex-col flex-1 min-w-0">
                        <div class="flex items-baseline space-x-2">
                            <flux:subheading size="lg" class="text-gray-500">Online meeting:
                                <a href="{{ $meeting['link'] }}" target="_blank" rel="noopener noreferrer"
                                    class="text-blue-600 hover:text-blue-800 underline break-all">
                                    Link
                                </a>
                            </flux:subheading>
                        </div>
                        @if (!empty($meeting['password']))
                            <div class="flex items-baseline mt-1 space-x-2">
                                <flux:subheading size="lg" class="text-gray-500">Password:
                                    <span class="text-gray-800 font-mono">{{ $meeting['password'] }}</span>
                                </flux:subheading>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center text-sm space-x-2">
                <flux:icon.calendar class="size-5 text-gray-400" />
                <flux:subheading size="lg" class="text-gray-800"><span class="text-gray-500">Tanggal:</span>
                    {{ $meeting['date'] }}
                </flux:subheading>
            </div>

            <div class="flex justify-between items-center text-sm">
                <div class="flex items-center space-x-2">
                    <flux:icon.clock class="size-5 text-gray-400" />
                    <flux:subheading size="lg" class="text-gray-800"><span class="text-gray-500">Waktu:</span>
                        {{ $meeting['start'] }} - {{ $meeting['end'] }}
                    </flux:subheading>
                </div>
            </div>
        </div>

        <!-- Right column -->
        <div class="flex-1 flex items-start text-sm min-h-[100px]">
            <div class="w-full">
                <div class="flex items-center space-x-2">
                    <flux:icon.chat-bubble-left-right class="size-5 text-gray-400" />
                    <flux:heading size="lg" class="text-gray-500">Hasil meeting: </flux:heading>
                </div>
                @if ($meeting['result'] === null)
                    <form wire:submit="updateResultMeeting({{ $key }})" class="space-y-4">
                        <div class="flex justify-between items-end gap-2">
                            <flux:textarea wire:model="result.{{ $key }}" placeholder="Input disini..." rows="2"
                                class="w-3/4 mt-3" />
                            <flux:button type="submit" size="sm" class="mr-4">Simpan</flux:button>
                        </div>
                    </form>
                @else
                    <flux:subheading size="lg" class="text-gray-800 break-words max-h-32 overflow-y-auto">
                        {{ $meeting['result'] }}
                    </flux:subheading>
                @endif
            </div>
        </div>
    </div>
    <flux:modal name="edit-meeting-{{ $key }}-modal" focusable class="md:w-120" size="lg">
        <form wire:submit="updateResultMeeting({{ $key }})">
            <section class="space-y-4">
                <flux:heading size="lg">Edit hasil meeting</flux:heading>
                <flux:textarea wire:model="result.{{ $key }}" placeholder="Input disini..." rows="2" />
                <div class="flex justify-end space-x-2">
                    <flux:button type="submit" size="sm">Simpan</flux:button>
                </div>
            </section>
        </form>
    </flux:modal>
</div>