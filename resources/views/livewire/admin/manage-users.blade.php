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
            <flux:button variant="primary" icon:variant="mini" icon:trailing="plus">Tambah</flux:button>
        </flux:modal.trigger>
    </div>

    <flux:table.base :perPage="$perPage" :paginate="$users">
        <x-slot name="header">
            <flux:table.column class="w-1 border-l-2 border-white dark:border-l-zinc-800">
                <flux:checkbox wire:click="toggleSelectAll" />
            </flux:table.column>
            <flux:table.column>Name</flux:table.column>
            <flux:table.column>Email</flux:table.column>
            <flux:table.column>Created date</flux:table.column>
        </x-slot>

        <x-slot name="body">
            @foreach ($users as $user)
                <tr wire:key="{{ $user->id }}" @click="$wire.show({{ $user->id }})" class="hover:bg-zinc-100 cursor-pointer">
                    <flux:table.row>
                        <div @click.stop class="py-3">
                            <flux:checkbox wire:model.live="selectedUsers" value="{{ $user->id }}" />
                        </div>
                    </flux:table.row>

                    <flux:table.row>{{ $user->name }}</flux:table.row>
                    <flux:table.row>{{ $user->email }}</flux:table.row>
                    {{-- <flux:table.row>
                        {{ ucfirst($user->roles->pluck('name')->implode(', ')) }}
                    </flux:table.row> --}}
                    <flux:table.row>{{ $user->created_at }}</flux:table.row>

                </tr>
            @endforeach
        </x-slot>
    </flux:table.base>

    <livewire:admin.create-user />

    {{-- <livewire:admin.update-user /> --}}

</div>