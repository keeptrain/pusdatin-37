@php
$layout = auth()->user()->hasRole(['administrator','verifikator'],) ? 'layouts.app.sidebar' : 'layouts.app.header';
@endphp

<x-dynamic-component :component="$layout" :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-dynamic-component>