@props([
    'discussion',
])
<div 
    wire:key="discussion-{{ $discussion->id }}"
    class="px-4 py-3 border rounded-lg space-y-3 hover:shadow-md transition-shadow"
>
    <!-- Discussion Header -->
    <div class="flex items-start justify-between gap-2">
        <x-discussions.badge 
            :status="$discussion->discussable_type" 
            :label="$discussion->discussableContext" 
            :id="$discussion->discussable_id" 
            class="flex-shrink-0"
        />
    </div>

    <!-- Discussion Content -->
    <div class="text-gray-800">
        {{ $discussion->body }}
    </div>

    <!-- Discussion Footer -->
    <div class="flex flex-col space-y-2">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <x-lucide-message-square class="w-4 h-4" />
                <span>{{ count($discussion['replies']) }} balasan</span>
                <x-lucide-image class="w-4 h-4" />
                <span>{{ $discussion->attachments_count + $discussion->replies->sum('attachments_count') }} gambar</span>
                @if (!empty($discussion->replies->last()->created_at))
                    <x-lucide-clock class="w-4 h-4" />
                    <span>{{ $discussion->replies->last()->created_at->format('d M Y - H:i') }}</span>
                @endif
            </div>
            <flux:button :href="route('discussion.show', $discussion->id)"
                size="sm" variant="ghost" icon:trailing="arrow-top-right-on-square">Lihat diskusi</flux:button>
        </div>
    </div>
</div>

