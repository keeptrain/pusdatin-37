<div>
  <div class="max-w-screen-lg mx-auto">
    <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0 mb-6">
      <div>
        <h2 class="text-2xl font-semibold text-gray-900">History Permohonan</h2>
        <p class="mt-1 text-sm text-gray-500">
          Seluruh permohonan layanan yang diajukan terdata disini.
        </p>
      </div>
    </div>
    <flux:table.base :perPage="$perPage" :paginate="$this->allRequests" emptyMessage="Belum terdapat permohonan">
      <x-slot name="header">
        <flux:table.column class="w-1 border-l-2 border-white dark:border-l-zinc-800">
        </flux:table.column>
        <flux:table.column>Tanggal diajukan</flux:table.column>
        <flux:table.column>Jenis Layanan</flux:table.column>
        <flux:table.column>Status</flux:table.column>
        <flux:table.column>Aksi</flux:table.column>
      </x-slot>

      <x-slot name="body">
        @foreach ($this->allRequests as $item)
      <tr @click="$wire.detailPage({{ $item->id }}, '{{ $item->type }}')" class="hover:bg-zinc-100 cursor-pointer">
        <flux:table.row>

        </flux:table.row>
        <flux:table.row>{{ $item->created_at }} </flux:table.row>
        <flux:table.row>{{ $item->type }}</flux:table.row>
        <flux:table.row> <flux:notification.status-badge :status="$item->status" /> </flux:table.row>
        <flux:table.row>
        <flux:dropdown @click.stop>
          <flux:button variant="ghost"></flux:button>
          <flux:menu>
          </flux:menu>
        </flux:dropdown>
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
      </x-slot>
    </flux:table.base>
  </div>