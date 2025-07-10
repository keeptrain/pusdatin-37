<div class="flex flex-col">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <flux:button :href="route($routeBack)" icon="arrow-long-left" variant="subtle">Kembali</flux:button>
        <flux:dropdown align="bottom" offset="-25" gap="2">
            <flux:button variant="ghost" icon="ellipsis-vertical">Aksi</flux:button>

            <flux:menu>
                <flux:menu.item wire:click="statusDiscussion" icon="pencil-square">{{ $status }}</flux:menu.item>
                <flux:menu.item wire:click="deleteDiscussion" icon="trash" variant="danger">Hapus</flux:menu.item>
            </flux:menu>
        </flux:dropdown>
    </div>
    <header class="p-3 space-y-2">
        <div class="flex justify-between lg:flex-row flex-col items-center">
            {{-- <flux:heading size="lg">Diskusi</flux:heading> --}}
            <x-discussions.badge :status="$discussion->discussable_type" :label="$discussion->discussableContext"
                :id="$discussion->discussable_id" class="flex-shrink-0" />
        </div>
        <div class="flex lg:flex-row flex-col items-center">
            <flux:heading size="xl">Pembahasan: {{ $discussion->body }}</flux:heading>
        </div>
        <div class="flex lg:flex-row flex-col items-center">
            @if ($firstAttachments->isNotEmpty())
                <div class="flex items-center gap-2">
                    <flux:text>Lampiran:</flux:text>
                    <x-discussions.attachments-list :attachments="$firstAttachments" />
                </div>
            @endif
        </div>
        <div class="space-y-2">
            @php
                use Carbon\Carbon;
                $closedAt = Carbon::parse($discussion->closed_at)->format('d M Y H:i') ?? '-';
            @endphp
            <flux:text>Tanggal dibuat: {{ $discussion->firstCreatedAt }}</flux:text>
            <flux:text>Tanggal selesai: {{ $discussion->closed_at ? $closedAt : '-' }}</flux:text>
        </div>
    </header>
    <flux:separator />

    <!-- Konten Diskusi -->
    <main class="flex-grow overflow-y-auto px-4 py-6 space-y-6">
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
                            <flux:legend>{{ $reply->body }}</flux:legend>
                        </div>
                        <x-discussions.attachments-list :attachments="$reply->attachments" />
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
                        <div class="flex justify-between space-y-1">
                            <flux:legend>{{ $reply->body }}</flux:legend>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-discussions.attachments-list :attachments="$reply->attachments" />
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
    </main>

    <flux:separator />

    <!-- Form Input Diskusi -->
    <footer class="px-4 py-3">
        <form wire:submit="reply" x-ref="replyForm" class="space-y-2">
            <div x-data="{ 
                uploading: false, 
                progress: 0
            }" x-on:livewire-upload-start="uploading = true; progress = 0"
                x-on:livewire-upload-finish="uploading = false; progress = 0"
                x-on:livewire-upload-error="uploading = false; progress = 0"
                x-on:livewire-upload-progress="progress = $event.detail.progress" class="flex flex-col space-y-2">
                <x-layouts.form.input-multiple-file :form="$form" :discussionId="$discussionId" />

                <flux:textarea wire:model="form.replyStates.{{ $discussionId }}.body" placeholder="Tulis balasan..."
                    rows="2"
                    class="w-full p-2 border border-gray-300 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required />
                <flux:text size="sm">*Untuk file selain gambar, tolong upload menggunakan
                    google drive lalu sisipkan link pada kolom di atas.</flux:text>
            </div>

            <div class="flex justify-end items-center">
                <flux:button type="submit" variant="primary" icon:trailing="paper-airplane">
                    Kirim
                </flux:button>
            </div>
        </form>
    </footer>
</div>