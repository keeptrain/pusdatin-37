@props([
    'href',
    'icon' => null,
    'heading',
    'description',  
])

<a href="{{ $href }}" class="block p-6 border-1 rounded-lg hover:shadow-sm dark:bg-zinc-900 dark:border-zinc-700 dark:hover:bg-zinc-700 ">
    <div class="flex flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            @if ($icon)
                <div class="relative">
                    @if (is_string($icon) && $icon !== '')
                        <flux:icon :icon="$icon" />
                    @else
                        {{ $icon }}
                    @endif
                </div>
            @endif
            <div>
                <flux:heading>{{ $heading }}</flux:heading>
                <flux:text class="mt-2">{{ $description }}</flux:text>
            </div>
        </div>
        <flux:button class="cursor-pointer" icon-trailing="arrow-right" variant="ghost" inset/>
    </div>
</a>