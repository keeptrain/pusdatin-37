@props([
    'icon',
    'title' => null,
    'data',
    'label' => null,
])
<div class="flex rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 space-y-4">
    <!-- Baris Pertama: Avatar, Total Services, dan Teks -->
    <div class="flex items-center space-x-4">
        <!-- Avatar -->
        <flux:avatar icon="{{ $icon }}" size="xl" color="auto" />
  
        <!-- Total Services dan Teks -->
        <div class="flex flex-col space-y-1">
            <div class="flex items-end gap-2">
                <flux:heading size="xl">{{ $data }}</flux:heading>
                <flux:heading size="lg">{{ $title }}</flux:heading>
            </div>
            <p class="text-sm font-medium text-neutral-500 dark:text-white">{{ $label }}</p>
        </div>
    </div>
</div>