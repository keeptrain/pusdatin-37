<div class="flex justify-between space-x-2">
    <flux:input wire:model.live.debounce.500ms="search" icon="magnifying-glass" placeholder="Cari diskusi..."
        :loading="false" class="flex-1" />

    <div class="flex items-center space-x-2">
        <!-- Sort Button -->
        <flux:button wire:click="sortToggle" icon="arrows-up-down">
            <span class="hidden sm:block">{{ $sort }}</span>
        </flux:button>

        <!-- Filter Button -->
        <flux:button @click="$dispatch('modal-show', { name: 'filter-discussion-modal' });" icon="eye">
            <span class="hidden sm:block">Filter</span>
        </flux:button>
    </div>
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