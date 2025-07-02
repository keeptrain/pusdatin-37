@props([
    'icon',
    'title' => null,
    'data',
    'label' => null,
])
<div class="relative rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 space-y-4">
    <!-- Baris Pertama: Avatar, Total Services, dan Teks -->
    <div class="flex items-center space-x-4">
        <!-- Avatar -->
        <flux:avatar icon="{{ $icon }}" size="lg" color="auto"></flux:avatar>

        <!-- Total Services dan Teks -->
        <div class="flex flex-col">
            <flux:heading size="xl">{{ $data }}</flux:heading>
            <flux:heading size="lg">{{ $title }}</flux:heading>
        </div>
    </div>

    <!-- Baris Kedua: Label -->
    <div class="flex items-center space-x-2">
        <p class="text-sm font-medium text-neutral-500 dark:text-white">{{ $label }}</p>
    </div>

    {{-- <div class="text-sm font-medium text-emerald-600 dark:text-emerald-500 flex items-center mb-5">
        <flux:icon.arrow-trending-up class="size-5 mr-2" />
        12% from last month
    </div> --}}
</div>