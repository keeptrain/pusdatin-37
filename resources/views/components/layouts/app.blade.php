@php
    $isUserLayout = auth()->user()->hasRole('user');
    $layout = $isUserLayout ? 'layouts.app.header' : 'layouts.app.sidebar';
@endphp

<x-dynamic-component :component="$layout" :title="$title ?? 'JakReq'">
    <flux:main :container="$isUserLayout">
        @if ($isUserLayout)
            <x-user.dashboard.warning-modal-sop />
        @endif
        {{ $slot }}
    </flux:main>
</x-dynamic-component>