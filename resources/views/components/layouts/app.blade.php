@php
  $roleUser = auth()->user()->hasRole('user');
  $layout = $roleUser ? 'layouts.app.header' : 'layouts.app.sidebar';
  $shouldUseContainer = $layout === 'layouts.app.header';
@endphp

<x-dynamic-component :component="$layout" :title="$title ?? 'JakReq'">
  <flux:main :container="$shouldUseContainer">
    {{ $slot }}
  </flux:main>
</x-dynamic-component>