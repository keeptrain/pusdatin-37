<div>
    <flux:breadcrumbs>
        <flux:breadcrumbs.item :href="route('letter')" wire:navigate>Letter</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>{{ $letter->title }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <x-letters.detail-layout :letterId="$letterId">

        <flux:notification.status-stepped :status="$letter->status->label()" />

        @if ($letter->active_revision)
            <flux:button wire:click="detailPage({{ $letterId }})" class="mt-6" wire:navigate> Revisi</flux:button>
        @endif

        @foreach ($activity as $item)
            <flux:text class="mt-6">{{ $item->created_at }}</flux:text>
            <flux:text>{{ $item->action }}</flux:text>
            <flux:text>{{ $item->created_by }}</flux:text>
            <flux:text>
                @if (!empty($item->notes))
                    Notes: {{ $item->notes }}
                @endif
            </flux:text>
        @endforeach

        {{-- <flux:table.base :perPage="$perPage" :paginate="$data">
            <x-slot name="header">
                <flux:table.column>Action</flux:table.column>
                <flux:table.column>Date</flux:table.column>
            </x-slot>

            <x-slot name="body">
                @foreach ($data as $item)
                    <tr>
                        <flux:table.row>
                            {{ $item->action }}
                        </flux:table.row>
                        <flux:table.row>{{ $item->created_at->format('d M Y H:i') }}</flux:table.row>


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

        </flux:table.base> --}}

    </x-letters.detail-layout>
</div>
