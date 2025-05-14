<div x-data="{ open: false }" class="border-l-2 border-amber-500 bg-amber-50">
    <div class="p-2 bg-amber-100 flex justify-between items-center" x-on:click="open = !open">
        <div class="flex items-center">
            <flux:icon.exclamation-circle class="text-amber-600 dark:text-amber-300"/>
            <flux:heading class="ml-2 text-amber-800">Notes</flux:heading>
        </div>
        <div>
            <template x-if="open">
                <flux:icon.chevron-up class="size-5 mr-1 text-amber-600"/>
            </template>
            <template x-if="!open">
                <flux:icon.chevron-down class="size-5 mr-1 text-amber-600"/>
            </template>
        </div>
    </div>

    <div class="p-3 text-amber-700" x-show="open">
        <p>{{ $note }}</p>
    </div>
</div>