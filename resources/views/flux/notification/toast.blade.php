@props([
    'variant' => null,
    'message' => null,
    'duration' => null,
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

    $icon = $icons[$variant];
    $colorClass = $colors[$variant] ?? $colors['info'];
@endphp

<div id="toast-container"
    class="flex flex-col fixed p-3 top-6 right-6 rounded-xl border-1 border-gray-300 shadow-md bg-white dark:bg-gray-800 text-black dark:text-white w-auto max-w-md">

    <!-- Top Row: Icon, Heading, and Close Button -->
    <div class="flex items-center justify-between  space-x-4">
        <!-- Icon dan Heading -->
        <div class="flex items-center space-x-2 flex-grow min-w-0">
            <flux:icon :name="$icon" variant="solid" class="{{ $colorClass }} size-5" />
            <div class="truncate">
                <flux:heading class="whitespace-nowrap">{{ ucfirst($variant) }}</flux:heading>
            </div>
        </div>

        <!-- Close Button -->
        <flux:button variant="ghost" icon="x-mark" alt="Close toast"
            class="flex-shrink-0 text-zinc-400 hover:text-zinc-800 dark:text-zinc-500 dark:hover:text-white transition duration-200 "
            onclick="this.closest('#toast-container').remove()" inset></flux:button>
    </div>

    <!-- Message -->
    <div class="pl-7 pr-7 break-words whitespace-normal">
        <flux:text variant="subtle">{{ $message }}</flux:text>
    </div>
</div>

<script>
    // Automatically remove the toast after the specified duration
    document.addEventListener('DOMContentLoaded', () => {
        const toastContainer = document.getElementById('toast-container');
        if (toastContainer) {
            setTimeout(() => {
                toastContainer.remove();
            }, {{ $duration }});
        }
    });
</script>
