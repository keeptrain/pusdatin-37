@props([
    'requestable' => null,
])
<flux:callout icon="user" class="mr-3" variant="secondary">
    <flux:callout.heading>Penanggung jawab</flux:callout.heading>

    <flux:callout.text>
        {{ $requestable->user->name }} dari seksi {{ $requestable->user->sectionLabel }} - No. Telp:
        {{ $requestable->user->contact }}
    </flux:callout.text>
</flux:callout>