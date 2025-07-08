@props([
    'status' => null,
])
@php
    $statusColors = [
        'Hari ini' => 'bg-blue-100 text-blue-800',
        'Sedang berlangsung' => 'bg-green-100 text-green-800',
        'Hari ini tetapi sudah lewat' => 'bg-amber-100 text-amber-800',
        'Sudah lewat' => 'bg-gray-100 text-gray-800',
    ];

    $defaultColor = 'bg-purple-100 text-purple-800';

    $colorClass = str_contains($status, 'hari lagi')
        ? $defaultColor
        : ($statusColors[$status] ?? 'bg-gray-100 text-gray-200');
@endphp
<div class="{{ $colorClass }} text-xs font-medium px-2.5 py-0.5 rounded-full mr-3">
    <span>{{ $status }}</span>
</div>