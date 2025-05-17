<section>
    @include('partials.create-letter-heading')

    <flux:legend>{{ $legend ?? '' }}</flux:legend>

    {{ $slot }}
</section>