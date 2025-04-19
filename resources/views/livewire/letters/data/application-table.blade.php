<div class="lg:p-3">
    <flux:heading size="xl" level="1" class="mb-6">{{ __('Letter') }}</flux:heading>
    <flux:menu.tabs :statuses="$statuses" :filterStatus="$filterStatus" />

    <div class="flex justify-start mb-4 h-10">
        @if (count($selectedLetters) > 0)
            <flux:dropdown class="mr-2">
                <flux:button class="bg-zinc-100 dark:bg-zinc-900" icon="ellipsis-vertical">
                    <span class="hidden lg:inline">Actions</span>
                </flux:button>

                <flux:menu>
                    <flux:modal.trigger name="confirm-letter-deletion">
                        <flux:menu.item variant="danger" icon="trash" x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-letter-deletion')">Delete
                            ({{ count($selectedLetters) }})</flux:menu.item>
                    </flux:modal.trigger>
                </flux:menu>

            </flux:dropdown>
        @endif
        <flux:dropdown>
            <flux:button class="bg-zinc-100 dark:bg-zinc-900" icon:trailing="chevron-down">Sort by</flux:button>

            <flux:menu>
                <flux:menu.radio.group wire:model="sortBy">
                    <flux:menu.radio checked>Latest activity</flux:menu.radio>
                    <flux:menu.radio>Date created</flux:menu.radio>
                </flux:menu.radio.group>
            </flux:menu>
        </flux:dropdown>
    </div>

    <flux:table.base :perPage="$perPage" :paginate="$letters" emptyMessage="No data letter available.">
        <x-slot name="header">
            <flux:table.column class="w-1 border-l-2 border-white dark:border-l-zinc-800">
                <flux:checkbox wire:click="toggleSelectAll" />
            </flux:table.column>
            <flux:table.column>Name</flux:table.column>
            <flux:table.column>Type</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column>Created date</flux:table.column>
            <flux:table.column></flux:table.column>
        </x-slot>

        <x-slot name="body">
            @foreach ($letters as $item)
                <tr class="
                    {{ in_array($item->id, $selectedLetters) ? 'relative bg-zinc-50 dark:bg-zinc-900 ' : 'dark:bg-zinc-800' }}
                    border-b border-b-zinc-100 dark:border-b-zinc-800 hover:bg-gray-100 dark:hover:bg-zinc-900 cursor-pointer"
                    wire:navigate>

                    <div>
                        <flux:table.row
                            class="{{ in_array($item->id, $selectedLetters)
                                ? '  border-s-2 border-black dark:border-white'
                                : 'border-l-2 border-white dark:border-l-zinc-800' }} ">
                            <flux:checkbox wire:model.live="selectedLetters" value="{{ $item->id }}" />
                        </flux:table.row>
                    </div>

                    <flux:table.row>
                        <a href="{{ route('letter.detail', $item->id) }}" wire:navigate>
                            {{ $item->user->name }}
                        </a>
                    </flux:table.row>
                    <flux:table.row>{{ $item->category_type_name }}</flux:table.row>
                    <flux:table.row>
                        <flux:notification.status-badge status="{{ $item->status->label() }}">
                            {{ $item->status->label() }}</flux:notification.status-badge>
                    </flux:table.row>
                    <flux:table.row>{{ $item->formatted_date }}</flux:table.row>
                    <flux:table.row>
                        <flux:button wire:click="editPage({{ $item->id }})" variant="ghost">Edit</flux:button>
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


    <flux:modal name="confirm-letter-deletion" focusable class="max-w-lg">
        <form wire:submit="deleteSelected" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Delete selected letters') }}</flux:heading>

                <flux:subheading>
                    {{ __('Are you sure you would like to do this?') }}
                </flux:subheading>
            </div>
            <div class="flex justify-end space-x-2">

                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" type="submit">{{ __('Confirm') }}</flux:button>

            </div>
        </form>
    </flux:modal>

</div>
