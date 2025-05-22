@props([
    'variant' => null,
    'message' => null,
    'duration' => 3000, // Default duration to 3000ms (3 seconds) if not provided
])

@php
    $icons = [
        'success' => 'check-circle',
        'error' => 'exclamation-circle',
        'warning' => 'exclamation-triangle',
        'info' => 'information-circle',
    ];

    $colors = [
        'success' => 'text-green-600 dark:text-green-300',
        'error' => 'text-red-600 dark:text-red-300',
        'warning' => 'text-yellow-600 dark:text-yellow-300',
        'info' => 'text-blue-600 dark:text-blue-300',
    ];

    $icon = $icons[$variant] ?? $icons['info'];
    $colorClass = $colors[$variant] ?? $colors['info'];
@endphp

<div
    x-data="{ show: true }"
    x-init="setTimeout(() => show = false, {{ $duration }})"
    x-show="show" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-3"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-3"
    id="toast-container"
    class="flex flex-col fixed p-3 top-6 right-6 rounded-xl border-1 border-gray-300 shadow-md bg-white dark:bg-gray-800 text-black dark:text-white w-auto max-w-md"
>
    <div class="flex items-center justify-between space-x-4">
        <div class="flex items-center space-x-2 flex-grow min-w-0">
            <flux:icon :name="$icon" variant="solid" class="{{ $colorClass }} size-5" />
            <div class="truncate">
                <flux:heading class="whitespace-nowrap">{{ ucfirst($variant) }}</flux:heading>
            </div>
        </div>

        <flux:button variant="ghost" icon="x-mark" alt="Close toast"
            class="flex-shrink-0 text-zinc-400 hover:text-zinc-800 dark:text-zinc-500 dark:hover:text-white transition duration-200"
            @click="show = false" inset>
        </flux:button>
    </div>

    <div class="pl-7 pr-7 break-words whitespace-normal">
        <flux:text variant="subtle">{{ $message }}</flux:text>
    </div>
</div>