@props([
    'perPage' => 10,
    'emptyMessage' => 'No data available.',
    'paginate' => [],
])

<div class="overflow-x-auto">

    <table class="bg-white min-w-full">
        <thead>
            <tr class="bg-gray-50 dark:bg-zinc-900 border-b dark:border-zinc-600">
                {{-- Slot untuk custom header --}}
                {{ $header }}
            </tr>
        </thead>

        <tbody>
            {{-- Slot untuk custom body --}}
            {{ $body }}

            {{-- Empty Rows --}}
            @php
                $currentPageItems = count($paginate);
                $emptyRows = $perPage - $currentPageItems;
            @endphp

            @if ($emptyRows > 0 && $currentPageItems > 0)
                @for ($i = 0; $i < $emptyRows; $i++)
                    <tr class="bg-gray-50 border-b dark:bg-zinc-900 dark:border-zinc-600">
                        {{ $emptyRow }}
                    </tr>
                @endfor
            @endif

            {{-- Empty State --}}
            @if (count($paginate) === 0)
                <tr>
                    <td class="px-6 py-4 text-center text-sm text-gray-500" colspan="6">
                        {{ $emptyMessage }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
    
</div>

<!-- Pagination -->
<div class="text-black dark:text-white mt-4 py-4">

    {{ $paginate->links(data: ['scrollTo' => false]) }}
    <div class="mb-4 w-18">
        <flux:select size="sm" wire:model.live.debounce.250ms="perPage" placeholder="Select per page">
            <flux:select.option value="10">10</flux:select.option>
            <flux:select.option value="15">15</flux:select.option>
            <flux:select.option value="25">25</flux:select.option>
        </flux:select>
    </div>

</div>
