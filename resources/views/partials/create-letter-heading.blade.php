<div class="relative mb-6 w-full">
    <div class="flex justify-between items-center">
        <div>
            <flux:heading size="xl" level="1">{{ __('Create letter') }}</flux:heading>
            <flux:breadcrumbs class="mt-2 mb-2">
                <flux:breadcrumbs.item :href="route('dashboard')" wire:navigate icon="home" />
                <flux:breadcrumbs.item :href="route('letter')" wire:navigate>Letter</flux:breadcrumbs.item>
                <flux:breadcrumbs.item >Upload</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>
        <div class="flex items-center">
            <flux:dropdown>
                <flux:button icon="ellipsis-horizontal" class="mr-2" />
                <flux:menu>
                    <flux:menu.radio.group>
                        <flux:menu.group heading="Save as">
                            <flux:menu.item wire:click="filter('draft')">{{ __('Draft') }}</flux:menu.item>
                            <flux:menu.item wire:click="filter('published')">{{ __('PDF') }}</flux:menu.item>
                        </flux:menu.group>
                    </flux:menu.radio.group>
                </flux:menu>
            </flux:dropdown>

            <flux:button variant="primary">{{ __('Create') }}</flux:button>
        </div>
    </div>
    <flux:separator variant="subtle" />
</div>
