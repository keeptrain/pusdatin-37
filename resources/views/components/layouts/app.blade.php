@php
    $layout = auth()->user()->hasRole(['administrator', 'head_verifier', 'si_verifier', 'data_verifier', 'pr_verifier'], ) ? 'layouts.app.sidebar' : 'layouts.app.header';
@endphp

<x-dynamic-component :component="$layout" :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-dynamic-component>