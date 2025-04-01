@props(['statuses', 'filterStatus'])

<div class="flex justify-center mb-6">
    <div
        class="flex border rounded-lg bg-zinc-50 border-gray-200 dark:border-gray-700 w-full overflow-x-auto whitespace-nowrap md:w-auto scrollbar-hidden">
        @foreach ($statuses as $status)
            <flux:button wire:click="$set('filterStatus', '{{ $status }}')" size="sm"
                variant="{{ $filterStatus === $status ? 'primary' : 'subtle' }}"
                class="px-2 py-2 m-2">
                {{ ucfirst($status) }}
            </flux:button>
        @endforeach
    </div>
</div>
