<section wire:click="goDetailPage('{{ $notification->id }}')">
    <div class="relative flex gap-3 p-4 bg-zinc-50 hover:bg-blue-50 transition cursor-pointer">
        <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-500">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                    </path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
            </div>
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between">
                <p class="text-sm font-medium text-gray-900">
                    {{ $notification->data['message'] ?? '-' }}
                </p>
            </div>
            <p class="mt-1 text-xs text-gray-500">
                {{ $notification->created_at->format('H:i') }} -
                {{ $notification->created_at->diffForHumans() }}
            </p>
        </div>
    </div>
</section>