<flux:dropdown>
    <flux:button icon="ellipsis-horizontal" class="w-full mt-3" />

    <flux:menu>
        {{-- <flux:menu.item :href="route('letter.edit', [$letterId])" icon="pencil-square">Force edit</flux:menu.item>
        --}}
        <flux:modal.trigger name="create-meeting-modal">
            <flux:menu.item x-on:click="$dispatch('modal-show', { name: 'create-meeting-modal' })"
                icon="video-camera" class="w-full">Meeting</flux:menu.item>
        </flux:modal.trigger>
        <flux:menu.item :href="route('letter.rollback', [$letterId])" icon="backward">Rollback</flux:menu.item>
        <flux:menu.item icon="trash" variant="danger">Delete</flux:menu.item>
    </flux:menu>
</flux:dropdown>