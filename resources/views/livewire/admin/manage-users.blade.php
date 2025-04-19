<div class="lg:p-3">
    <flux:heading size="xl" level="1" class="mb-6">{{ __('Manage Users') }}</flux:heading>

    <div class="flex justify-between mb-4 h-10">
        <div class="flex items-center">
            @if (count($selectedUsers) > 0)
                <flux:dropdown class="mr-2">
                    <flux:button class="bg-zinc-100 dark:bg-zinc-900" icon="ellipsis-vertical">
                        <span class="hidden lg:inline">Actions</span>
                    </flux:button>

                    <flux:menu>
                        <flux:modal.trigger name="confirm-users-deletion">
                            <flux:menu.item variant="danger" icon="trash" x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'confirm-users-deletion')"
                                wire:click="deleteUsers">Delete
                                ({{ count($selectedUsers) }})</flux:menu.item>
                        </flux:modal.trigger>
                    </flux:menu>

                </flux:dropdown>
            @endif
            <flux:dropdown>
                <flux:button class="bg-zinc-100 dark:bg-zinc-900 justify-self-start" icon:trailing="chevron-down">Sort
                    by</flux:button>

                <flux:menu>
                    <flux:menu.radio.group wire:model="sortBy">
                        <flux:menu.radio value="latest_activity">Latest activity</flux:menu.radio>
                        <flux:menu.radio value="date_created">Date created</flux:menu.radio>
                    </flux:menu.radio.group>
                </flux:menu>
            </flux:dropdown>
        </div>

        <flux:modal.trigger name="create-user" wire:click="createPage">
            <flux:button variant="primary" icon:variant="mini" icon:trailing="plus-circle">Add</flux:button>
        </flux:modal.trigger>
    </div>

    <flux:table.base :perPage="$perPage" :paginate="$users">
        <x-slot name="header">
            <flux:table.column class="w-1 border-l-2 border-white dark:border-l-zinc-800">
                <flux:checkbox wire:click="toggleSelectAll" />
            </flux:table.column>
            <flux:table.column>Name</flux:table.column>

            <flux:table.column>Email</flux:table.column>
            <flux:table.column>Role</flux:table.column>

            <flux:table.column>Created date</flux:table.column>
            <flux:table.column></flux:table.column>
        </x-slot>

        <x-slot name="body">
            @foreach ($users as $user)
            
                <tr class="
                    {{ in_array($user->id, $selectedUsers) ? 'relative bg-zinc-50 dark:bg-zinc-900 ' : 'dark:bg-zinc-800' }}
                    border-b border-b-zinc-100 dark:border-b-zinc-800 hover:bg-gray-100 dark:hover:bg-zinc-900 cursor-pointer"
                    wire:navigate>

                    <flux:table.row
                        class="{{ in_array($user->id, $selectedUsers)
                            ? '  border-s-2 border-black dark:border-white'
                            : 'border-l-2 border-white dark:border-l-zinc-800' }} ">
                        <flux:checkbox wire:model.live="selectedUsers" value="{{ $user->id }}" />
                    </flux:table.row>

                    <flux:table.row>{{ $user->name }}</flux:table.row>
                    <flux:table.row>{{ $user->email }}</flux:table.row>
                    <flux:table.row>
                        {{ ucfirst($user->roles->pluck('name')->implode(', ')) }}
                    </flux:table.row>
                    <flux:table.row>{{ $user->created_at }}</flux:table.row>

                    <flux:table.row>
                        <flux:modal.trigger name="update-user" wire:click="updatePage({{ $user->id }})">
                            <flux:button variant="ghost" icon:variant="mini" >Edit
                            </flux:button>
                        </flux:modal.trigger>
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

    <livewire:admin.create-user/>

    <livewire:admin.update-user/>
    
</div>
