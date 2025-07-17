<div class="flex justify-between space-x-2">
    <flux:input wire:model.live.debounce.500ms="search" size="sm" icon="magnifying-glass" placeholder="Cari diskusi..."
        :loading="false" />

    <flux:button wire:click="sortToggle" size="sm" icon="arrows-up-down">{{ $sort }}</flux:button>

    <flux:button @click="$dispatch('modal-show', { name: 'filter-discussion-modal' });" size="sm" icon="eye">
        Filter</flux:button>
</div>

<flux:modal name="filter-discussion-modal" class="w-full max-w-md">
    <form wire:submit="refreshPage" class="space-y-4">
        <flux:legend>Filter Diskusi</flux:legend>

        <flux:select wire:model="discussableType" label="Kategori diskusi" placeholder="Pilih kategori">
            <option value="all">Semua</option>
            <option value="yes">Terkait permohonan</option>
            <option value="no">Tidak terkait</option>
        </flux:select>

        <flux:radio.group wire:model="status" label="Status diskusi">
            <flux:radio label="Telah selesai" value="closed" />
            <flux:radio label="Belum selesai" value="open" />
        </flux:radio.group>

        <div class="flex justify-end">
            <flux:button type="submit" size="sm">Terapkan</flux:button>
        </div>
    </form>
</flux:modal>