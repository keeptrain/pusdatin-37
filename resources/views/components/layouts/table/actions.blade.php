<flux:dropdown>
    <flux:button icon="ellipsis-vertical">Aksi</flux:button>

    <flux:menu>
        <flux:menu.item variant="danger"
            x-on:click="$dispatch('modal-show', { name: 'confirm-deletion' }); sendSelectedId();" icon="trash"
            x-bind:disabled="selectedId.length === 0">Hapus data <span x-text="`(${selectedId.length})`"></span>
        </flux:menu.item>
    </flux:menu>
</flux:dropdown>

<div class="flex-shrink-0">
    <flux:input type="text" id="globalSearch" placeholder="Search..." icon="magnifying-glass" clearable />
</div>