@props([
    'perPage' => 10,
    'paginate' => [],
])

<div class="overflow-x-auto">

    <table class="bg-white min-w-full">
        <thead>
            {{-- Slot untuk custom header --}}
            <tr class="bg-zinc-50 dark:bg-zinc-900 border-b dark:border-zinc-600">
                {{ $header }}
            </tr>
        </thead>

        {{-- Slot untuk custom body --}}
        <tbody>
            {{ $body }}
        </tbody>
    </table>
    
</div>

<!-- Pagination -->
<div class="text-black dark:text-white mt-4 py-4">

    {{ $paginate->links(data: ['scrollTo' => false]) }}

    <div class="mb-4 w-18">
        <flux:select size="sm" wire:model.live.debounce.500ms="perPage" placeholder="Select per page">
            <flux:select.option value="10">10</flux:select.option>
            <flux:select.option value="15">15</flux:select.option>
            <flux:select.option value="25">25</flux:select.option>
        </flux:select>
    </div>

</div>
