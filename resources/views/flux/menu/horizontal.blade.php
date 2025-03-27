@props([
    'href',
    'icon' => null,
    'heading',
    'description',  
])

<a href="{{ $href }}" class="block p-6 border-1 rounded-lg hover:shadow-sm transition duration-300 ease-in-out">
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
                <h2 class="text-md">{{ $heading }}</h2>
                <p class="text-gray-500 text-sm">{{ $description }}</p>
            </div>
        </div>
        <flux:button icon-trailing="arrow-right" variant="ghost"/>
    </div>
</a>