@props([
    'status' => null,
])

<flux:badge class="flex items-center "
    :color="match($status) {
        'Pending' => 'lime',
        'Process' => 'sky',
        'Replied' => 'yellow',
        'Approved' => 'green',
        'Rejected' => 'red',
        default => 'zinc'
    }">
    {{ $slot }}
</flux:badge>
