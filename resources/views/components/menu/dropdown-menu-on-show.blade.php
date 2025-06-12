<flux:dropdown>
    <flux:button icon="ellipsis-horizontal" class="w-full mt-3" />

    <flux:menu>
        {{-- <flux:menu.item :href="route('letter.edit', [$letterId])" icon="pencil-square">Force edit</flux:menu.item>
        --}}
        <flux:menu.item :href="route('letter.rollback', [$systemRequestId])" icon="backward">Rollback</flux:menu.item>
        {{-- <flux:menu.item icon="trash" variant="danger">Delete</flux:menu.item> --}}
    </flux:menu>
</flux:dropdown>