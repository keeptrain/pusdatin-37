<div class="relative mb-6 w-full">

    <div class="flex items-start">
        <div class="mb-6 ">
            <flux:breadcrumbs>
                {{-- <flux:breadcrumbs.item :href="route('dashboard')" wire:navigate icon="home" /> --}}
                <flux:breadcrumbs.item :href="route('dashboard')" wire:navigate>Permohonan</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>Sistem Informasi & data</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>Ajukan</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>
    </div>

    <flux:separator variant="subtle" />
</div>