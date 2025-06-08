<div class="bg-zinc-50 border rounded-lg p-4">
    <div class="flex items-center justify-between mb-4">
        <p class="text-xs font-medium text-gray-500 uppercase">{{ $meeting['status']}}</p>
        <flux:dropdown>
            <flux:button variant="ghost" icon="ellipsis-vertical" />
            <flux:menu>
                <flux:menu.item x-on:click="$dispatch('modal-show', { name: 'create-meeting-modal' })"
                    icon="video-camera" class="w-full">Edit</flux:menu.item>
                <flux:menu.item wire:click="delete({{ $key }})" icon="trash" variant="danger">Delete
                </flux:menu.item>
            </flux:menu>
        </flux:dropdown>
    </div>

    <div class="flex flex-col md:flex-row gap-4">
        <!-- Kolom Kiri -->
        <div class="flex-1 space-y-3 min-h-[150px]">
            <div class="flex items-start text-sm">
                @if (isset($meeting['location']))
                    <section class="flex items-center space-x-2">
                        <flux:icon.map-pin class="size-6 text-gray-400" />
                        <flux:subheading size="lg" class="text-gray-800">
                            <span class="text-gray-500">Lokasi:</span> {{ $meeting['location'] }}
                        </flux:subheading>
                    </section>
                @elseif (isset($meeting['link']))
                    <section class="flex items-center space-x-2">
                        <flux:icon.video-camera class="size-5 text-gray-500" />
                        <flux:subheading size="lg" class="text-gray-800">
                            <span class="text-gray-500">Online meeting:</span>
                            <a href="{{ $meeting['link'] }}" target="_blank" rel="noopener noreferrer"
                                class="text-blue-600 hover:text-blue-800 underline break-words">
                                Link
                            </a>
                        </flux:subheading>
                    </section>
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

        <!-- Kolom Kanan -->
        <div class="flex-1 flex items-start text-sm min-h-[150px]">
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
</div>