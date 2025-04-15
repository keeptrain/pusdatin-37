@props([
    'status' => null,
])

<flux:badge class="flex items-center "
    :color="match($status) {
        'New' => 'lime',
        'Read' => 'sky',
        'Replied' => 'yellow',
        'Closed' => 'red',
        default => 'warning'
    }">
    {{ $slot }}
</flux:badge>
