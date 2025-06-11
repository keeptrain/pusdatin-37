<div class="ml-1 mr-1 mx-auto">
  <div class="flex flex-col space-y-4 md:flex-col   md:space-y-0 mb-6">
    <flux:heading size="lg">Daftar Permohonan</flux:heading>
    <flux:subheading>Seluruh permohonan layanan yang diajukan terdata disini.</flux:subheading>
  </div>
  <flux:table.base :perPage="$perPage" :paginate="$this->allRequests" emptyMessage="Belum terdapat permohonan">
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
      @foreach ($this->allRequests as $item)
      <tr @click="$wire.detailPage({{ $item->id }}, '{{ $item->type }}')" class="hover:bg-zinc-100 cursor-pointer">
      <td class="py-3">&nbsp;</td>
      <flux:table.row>{{ $item->created_at }} </flux:table.row>
      <flux:table.row>{{ $item->information }}</flux:table.row>
      <flux:table.row>{{ $item->type }}</flux:table.row>
      <flux:table.row>
        <flux:notification.status-badge :status="$item->status" />
      </flux:table.row>
      <flux:table.row>
        <div @click.stop>
        @if ($item->active_revision)
      <flux:button size="sm" href="{{ route('letter.edit', $item->id) }}" wire:navigate>Revisi</flux:button>
      @endif
        </div>
      </flux:table.row>
      </tr>
    @endforeach
    </x-slot>

    <x-slot name="emptyRow">
      <td class="py-3">&nbsp;</td>
      <td class="py-3">&nbsp;</td>
      <td class="py-3">&nbsp;</td>
      <td class="py-3">&nbsp;</td>
      <td class="py-3">&nbsp;</td>
      <td class="py-3">&nbsp;</td>
    </x-slot>
  </flux:table.base>
</div>