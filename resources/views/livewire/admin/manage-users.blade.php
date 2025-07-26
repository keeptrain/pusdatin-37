<div>
    <flux:heading size="xl">{{ __(key: 'Manajemen Users') }}</flux:heading>
    <flux:subheading>
        {{ __('Daftar user yang terdaftar di sistem.') }}
    </flux:subheading>

    <div class="flex justify-between mt-6 mb-4 h-10">
        <div class="flex items-center">
            @if (count($selectedUsers) > 0)
                <flux:dropdown class="mr-2">
                    <flux:button icon="ellipsis-vertical">
                        <span class="hidden lg:inline">Actions</span>
                    </flux:button>

                    <flux:menu>
                        <flux:modal.trigger name="confirm-users-deletion">
                            <flux:menu.item variant="danger" icon="trash"
                                x-on:click="$dispatch('modal-show', 'confirm-users-deletion')">Delete
                                ({{ count($selectedUsers) }})</flux:menu.item>
                        </flux:modal.trigger>
                    </flux:menu>

                </flux:dropdown>
            @endif
            <flux:dropdown>
                <flux:button icon:trailing="chevron-down">
                    Sort by
                </flux:button>

                <flux:menu>
                    <flux:menu.radio.group wire:model.live.debounce.500ms="sortBy">
                        <flux:menu.radio value="latest_activity">Latest activity</flux:menu.radio>
                        <flux:menu.radio value="date_created">Date created</flux:menu.radio>
                    </flux:menu.radio.group>
                </flux:menu>
            </flux:dropdown>
        </div>

        <div class="flex items-center space-x-2">
            <flux:input wire:model.live.debounce.500ms="search" icon="magnifying-glass" placeholder="Cari user..."
                :loading="false" />
            <flux:modal.trigger name="create-user">
                <flux:button variant="primary" icon="plus">User</flux:button>
            </flux:modal.trigger>
        </div>
    </div>

    <flux:table.base :perPage="$perPage" :paginate="$users">
        <x-slot name="header">
            <flux:table.column class="w-1 border-l-2 border-white dark:border-l-zinc-800">
                {{--
                <flux:checkbox wire:click="toggleSelectAll" /> --}}
            </flux:table.column>
            <flux:table.column>Name</flux:table.column>
            <flux:table.column>Email</flux:table.column>
            <flux:table.column>Created date</flux:table.column>
        </x-slot>

        <x-slot name="body">
            @foreach ($users as $user)
                <tr wire:key="{{ $user->id }}" x-on:click="Livewire.navigate('{{ route('user.show', $user->id) }}')"
                    class="hover:bg-zinc-100 cursor-pointer">
                    <flux:table.row>
                        <div @click.stop class="py-3">
                            <flux:checkbox wire:model.live.debounce.500ms="selectedUsers" value="{{ $user->id }}" />
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

    <flux:modal name="confirm-users-deletion" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
        <form wire:submit="deleteUsers" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Apakah Anda yakin ingin menghapus akun ini?') }}</flux:heading>

                <flux:subheading>
                    {{ __('Setelah akun dihapus, semua data akan dihapus secara permanen. 
                    Silahkan masukkan password Anda untuk memastikan Anda ingin menghapus akun yang di pilih.') }}
                </flux:subheading>
            </div>

            <flux:input wire:model="form.password" :label="__('Password')" type="password" x-ref="passwordInput"
                x-on:input="$refs.submitBtn.disabled = $event.target.value === ''" />

            <div class="flex justify-end space-x-2">
                <flux:modal.close>
                    <flux:button variant="subtle">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" type="submit" x-ref="submitBtn" disabled>
                    {{ __('Delete account') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>