<div class="relative mb-6 w-full">

    <div class="flex items-start">
        <div class="mb-6 ">
            <flux:breadcrumbs>
                {{-- <flux:breadcrumbs.item :href="route('dashboard')" wire:navigate icon="home" /> --}}
                <flux:breadcrumbs.item :href="route('letter')" wire:navigate>Letter</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>Create</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>
    </div>

    <flux:separator variant="subtle" />
</div>