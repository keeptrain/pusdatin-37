<div class="border rounded-lg overflow-hidden">
    <div class="flex bg-zinc-50 items-center justify-between px-4 py-2 border-b">
        <x-menu.information-system.meeting-status :status="$meeting['status']" />
        <flux:dropdown>
            <flux:button variant="ghost" icon="ellipsis-vertical" />
            <flux:menu>
                <flux:menu.item
                    href="{{ route('is.meeting.edit', ['id' => $systemRequestId, 'meetingId' => $meeting['id']]) }}"
                    icon="pencil-square" class="w-full">Edit
                </flux:menu.item>
                <flux:menu.item wire:click="delete('{{ $meeting['id'] }}')" icon="trash" variant="danger"
                    :disabled="$meeting['pastEndDate']">Delete
                </flux:menu.item>
            </flux:menu>
        </flux:dropdown>
    </div>

    <div class="flex flex-col md:flex-row gap-4 p-4">
        <!-- Left column -->
        <div class="flex-1 space-y-3 min-h-[100px]">
            <div class="flex items-start text-sm space-x-2">
                @if ($meeting['place']['type'] == 'location')
                    <div class="flex-shrink-0 mt-1">
                        <flux:icon.map-pin class="size-5 text-gray-400" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <flux:subheading size="lg" class="text-gray-500">Lokasi:
                            <span class="text-gray-800">{{ $meeting['place']['value'] }}</span>
                        </flux:subheading>
                    </div>
                @elseif ($meeting['place']['type'] == 'link')
                    <div class="flex-shrink-0 mt-1">
                        <flux:icon.video-camera class="size-5 text-gray-400" />
                    </div>
                    <div class="flex flex-col flex-1 min-w-0">
                        <div class="flex items-baseline space-x-2">
                            <flux:subheading size="lg" class="text-gray-500">Online meeting:
                                <a href="{{ $meeting['place']['value'] }}" target="_blank" rel="noopener noreferrer"
                                    class="text-blue-600 hover:text-blue-800 underline break-all">
                                    Link
                                </a>
                            </flux:subheading>
                        </div>
                        @if ($meeting['place']['type'] == 'link' && !empty($meeting['place']['password']))
                            <div class="flex items-baseline mt-1 space-x-2">
                                <flux:subheading size="lg" class="text-gray-500">Password:
                                    <span class="text-gray-800 font-mono">{{ $meeting['place']['password'] }}</span>
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
                        {{ $meeting['start_at'] }} - {{ $meeting['end_at'] }}
                    </flux:subheading>
                </div>
            </div>
        </div>

        <!-- Right column -->
        <div class="flex-1 flex items-start text-sm min-h-[100px]">
            <div class="w-full">
                <div class="flex items-start space-x-2">
                    <flux:icon.clipboard class="size-5 text-gray-400" />
                    <flux:heading size="lg" class="text-gray-500">Topik: </flux:heading>
                </div>
                <flux:subheading size="lg" class="text-gray-800 mb-2">{{ $meeting['topic'] }}</flux:subheading>
                @if ($meeting['pastEndDate'] && $meeting['result'] === null)
                    <div class="flex items-center space-x-2">
                        <flux:icon.chat-bubble-left-right class="size-5 text-gray-400" />
                        <flux:heading size="lg" class="text-gray-500">Hasil meeting: </flux:heading>
                    </div>
                    <form wire:submit="updateResultMeeting('{{ $meeting['id'] }}')" class="space-y-4">
                        <div class="flex justify-between items-end gap-2">
                            <flux:textarea wire:model="result.{{ $meeting['id'] }}" placeholder="Input disini..." rows="2"
                                class="w-3/4 mt-3" />
                            @error('result')
                                <flux:subheading size="lg" class="text-red-500">{{ $message }}</flux:subheading>
                            @enderror
                            <flux:button type="submit" size="sm" class="mr-4">Simpan</flux:button>
                        </div>
                    </form>
                @elseif ($meeting['pastEndDate'] && $meeting['result'] !== null)
                    <div class="flex items-center space-x-2">
                        <flux:icon.chat-bubble-left-right class="size-5 text-gray-400" />
                        <flux:heading size="lg" class="text-gray-500">Hasil meeting: </flux:heading>
                    </div>
                    <flux:subheading size="lg" class="text-gray-800 break-words max-h-32 overflow-y-auto">
                        {{ $meeting['result'] }}
                    </flux:subheading>
                @endif
            </div>
        </div>
    </div>
</div>