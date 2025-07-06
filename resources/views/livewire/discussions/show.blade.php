@php
    $backPage = auth()->user()->hasRole('user') ? 'dashboard' : 'discussions';
@endphp

<div class="flex flex-col h-screen">
    <!-- Header -->
    <div>
        <flux:button :href="route($backPage)" icon="arrow-long-left" variant="subtle">Kembali</flux:button>
    </div>
    <div class="px-4 py-3 space-y-2">
        <div class="flex justify-between items-center gap-2">
            <flux:heading size="xl">Diskusi</flux:heading>
            <x-discussions.badge :status="$discussion->discussable_type" :label="$discussion->discussableContext"
                :id="$discussion->discussable_id" class="flex-shrink-0" />
            <flux:button size="sm" icon="check">Selesaikan diskusi</flux:button>
        </div>
        <flux:subheading>Pembahasan: {{ $discussion->body }}</flux:subheading>

        <div class="flex lg:flex-row flex-col justify-between items-center gap-2">
            <flux:text>Lampiran awal: </flux:text>
            <flux:text>Tanggal dibuat: {{ $discussion->firstCreatedAt }}</flux:text>
        </div>
    </div>
    <flux:separator />

    <!-- Konten Diskusi -->
    <div class="flex-grow overflow-y-auto px-4 py-6 space-y-6">
        @forelse ($replies as $reply)
            @php
                $isCurrentUser = $reply->user_id === auth()->id();
            @endphp
            <div wire:key="reply-{{ $reply->id }}" class="flex space-x-4 space-y-2">
                <flux:avatar initials="{{ $reply->user->initials() }}" size="lg" />

                @if (!$isCurrentUser)
                    <div class="flex flex-col space-y-1 w-full">
                        <div class="flex items-center justify-between">
                            <flux:text class="font-semibold">{{ $reply->user->name }}</flux:text>

                            {{-- <flux:text>Lampiran</flux:text> --}}
                        </div>

                        <flux:text class="text-xs">menjawab {{ $reply->created_at->diffForHumans() }}</flux:text>
                        <div>
                            <p class="text-gray-700">{{ $reply->body }}</p>
                        </div>
                    </div>
                @endif

                @if ($isCurrentUser)
                    <div class="flex flex-col space-y-1 bg-zinc-50 p-2 w-full border rounded-lg">
                        <div class="flex justify-between">
                            <flux:text class="font-semibold">{{ $reply->user->name }}</flux:text>
                            <a wire:click="deleteReply({{ $reply->id }})">
                                <flux:icon.trash class="size-5" />
                            </a>
                        </div>
                        <flux:text class="text-xs">menjawab {{ $reply->created_at->diffForHumans() }}</flux:text>
                        <div>
                            <p class="text-gray-700">{{ $reply->body }}</p>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="flex flex-col items-center justify-center h-[calc(80vh-12rem)] space-y-4">
                <flux:icon.chat-bubble-bottom-center-text class="mx-auto size-15 text-zinc-500 dark:text-amber-300" />
                <flux:text class="text-center">Belum ada balasan untuk diskusi ini.</flux:text>
            </div>
        @endforelse
    </div>

    <flux:separator />

    <!-- Form Input Diskusi -->
    <div class="px-4 py-3">
        <form wire:submit="reply" x-ref="replyForm" class="space-y-2">
            <div>
                <flux:textarea wire:model="form.replyStates.{{ $discussionId }}.body" placeholder="Tulis balasan..."
                    rows="2"
                    class="w-full p-2 border border-gray-300 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
            <div class="flex justify-end">
                <flux:button type="submit" variant="primary" icon:trailing="paper-airplane">
                    Kirim
                </flux:button>
            </div>
        </form>
    </div>
</div>