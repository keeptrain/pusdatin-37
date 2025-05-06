<div wire:poll.visible="loadMessages">
    <flux:button :href="route('letter.table')" icon="arrow-long-left" variant="subtle">Back to Table</flux:button>

    <x-letters.detail-layout :letterId="$letterId">
        <div class="mt-3">
            <!-- Header Surat -->
            <div class="bg-gray-50 px-4 py-3 flex justify-between items-center border rounded-t-lg">
                <div class="text-sm text-gray-500">
                    Title of letter
                </div>
            </div>

            <!-- Chat Body Scrollable -->
            @if ($messages->isNotEmpty())
                <div class="p-6 bg-zinc-50 space-y-6 overflow-y-auto h-[450px]" id="chat-container">
                    @foreach ($messages as $message)
                        @if ($message->sender_id === Auth::id())
                            <!-- Message dari user yang sedang login (kanan) -->
                            <div x-data x-init="$el.classList.add('opacity-0'); setTimeout(() => $el.classList.remove('opacity-0'), 10)" class="transition-opacity duration-300">
                                <div class="flex-1 flex flex-col items-end space-y-1">
                                    <div class="text-xs text-gray-500">
                                        Anda ke {{ $message->receiver->name ?? 'User' }}
                                    </div>
                                    <div class="bg-zinc-300 p-3 rounded-lg relative inline-block max-w-xs chat-bubble-right">
                                        <p class="text-sm text-gray-800">{{ $message->body }}</p>
                                    </div>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <span>{{ $message->created_at->format('H:i A / Y-m-d') }}</span>
                                    </div>
                                </div>
                            </div>

                        @else
                            <!-- Message dari lawan bicara (kiri) -->
                            <div class="flex items-start space-x-2">
                                <div class="w-8 h-8 rounded-full bg-teal-400 flex items-center justify-center text-white shrink-0">
                                    <span class="text-sm">{{ strtoupper(substr($message->sender->name ?? 'U', 0, 1)) }}</span>
                                </div>
                                <div class="flex-1 space-y-1">
                                    <div class="text-xs text-gray-500">
                                        {{ $message->sender->name ?? 'User' }} ke Anda
                                    </div>
                                    <div class="bg-gray-100 p-3 rounded-lg relative inline-block max-w-xs chat-bubble-left">
                                        <p class="text-sm text-gray-800">{{ $message->body }}</p>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $message->created_at->format('H:i A / Y-m-d') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="p-4 text-center text-sm text-gray-500 bg-zinc-50 h-[450px] flex items-center justify-center">
                    Belum ada pesan untuk surat ini.
                </div>
            @endif

            <!-- Chat Input -->
            <form wire:submit.prevent="send">
                <div class="mt-2 flex items-center">
                    <flux:input wire:model.defer="body" type="text" placeholder="Type a message..."
                        class="flex-1 text-sm outline-none" required/>
                    <flux:button type="submit" class="ml-2 text-blue-500">
                        <flux:icon.paper-airplane />
                    </flux:button>
                </div>
            </form>

    </x-letters.detail-layout>
</div>