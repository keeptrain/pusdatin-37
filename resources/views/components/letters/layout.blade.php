<div class="flex flex-col ">
    @include('partials.create-letter-heading')

    <flux:legend>{{ $legend ?? '' }}</flux:legend>

    <div class="mt-6">
        {{ $slot }}
    </div>
</div>