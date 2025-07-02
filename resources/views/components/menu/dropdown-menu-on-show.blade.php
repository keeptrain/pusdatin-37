<flux:dropdown>
    <flux:button icon="ellipsis-horizontal" class="w-full mt-3" />

    <flux:menu>
        <flux:modal.trigger name="email-modal">
            <flux:menu.item icon="envelope">
                Kirim email
            </flux:menu.item>
        </flux:modal.trigger>
        <flux:menu.item :href="route('is.rollback', [$systemRequestId])" icon="backward">Rollback</flux:menu.item>
        {{-- <flux:menu.item icon="trash" variant="danger">Delete</flux:menu.item> --}}
    </flux:menu>
</flux:dropdown>