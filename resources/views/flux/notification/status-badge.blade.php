@props([
    'status' => null,
])

<flux:badge class="flex items-center "
    :color="match($status) {
        'Pending' => 'amber',
        'Disposition' => 'yellow',
        'Process' => 'sky',
        'Replied' => 'pink',
        'Approved by Kasatpel' => 'lime',
        'Approved by Kapusdatin' => 'green',
        'Rejected' => 'red',
        default => 'zinc'
    }">
    {{ $slot }}
</flux:badge>
