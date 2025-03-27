<div class="flex flex-col p-6">
    @include('partials.create-letter-heading')

    <flux:legend>{{ $legend ?? '' }}</flux:legend>

    <div class="mt-6">
        {{ $slot }}
    </div>
</div>
