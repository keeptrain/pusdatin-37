<div class="ml-1 mr-1">
    <div class="flex flex-col space-y-4 md:flex-col md:space-y-0 mb-6">
        <flux:heading size="lg">Daftar Permohonan</flux:heading>
        <div class="flex items-center justify-between">
            <flux:subheading>Seluruh permohonan layanan yang diajukan terdata disini.</flux:subheading>
            <flux:input icon="magnifying-glass" placeholder="Cari permohonan..." wire:model.blur="search"
                class="!w-1/4" />
        </div>
    </div>

    <flux:table.base :perPage="$perPage" :paginate="$this->allRequests">
        <x-slot name="header">
            <flux:table.column>
            </flux:table.column>
            <flux:table.column>Tanggal diajukan</flux:table.column>
            <flux:table.column>Judul / Tema</flux:table.column>
            <flux:table.column>Jenis Layanan</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column>Aksi</flux:table.column>
        </x-slot>

        <x-slot name="body">
            @php
                $typeMapping = ['Sistem Informasi & Data' => 'information-system', 'Kehumasan' => 'public-relation'];
            @endphp
            @foreach ($this->allRequests as $item)
                <tr x-on:click="Livewire.navigate('{{ route('detail.request', ['type' => $typeMapping[$item->type], 'id' => $item->id]) }}')"
                    class="hover:bg-zinc-100 dark:hover:bg-zinc-700 cursor-pointer">
                    <td class="py-3 dark:bg-zinc-900">&nbsp;</td>
                    <flux:table.row>{{ $item->created_at }} </flux:table.row>
                    <flux:table.row>{{ $item->information }}</flux:table.row>
                    <flux:table.row>{{ $item->type }}</flux:table.row>
                    <flux:table.row>
                        <flux:notification.status-badge :status="$item->status" />
                    </flux:table.row>
                    <flux:table.row>
                        <div @click.stop>
                            @if ($item->active_revision)
                                <flux:button size="sm" href="{{ route('is.edit', $item->id) }}" wire:navigate>Revisi
                                </flux:button>
                            @endif
                        </div>
                    </flux:table.row>
                </tr>
            @endforeach
        </x-slot>
    </flux:table.base>
</div>