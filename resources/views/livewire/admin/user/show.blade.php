<div>
    <flux:button :href="route('manage.users')" icon="arrow-long-left" variant="subtle">Kembali ke Tabel</flux:button>

    <flux:heading size="xl" level="1" class="p-4">{{ __('Detail User') }}</flux:heading>

    <div class="max-w-screen-xl mb-6 ml-4">
        <div class="p-6 border border-gray-200 rounded-lg overflow-hidden space-y-4">
            <!-- Header: Request ID & Status -->
            <div class="flex justify-between">
                <div class="flex flex-col md:flex-row md:items-center gap-4 ">
                    <flux:avatar size="xl" :initials="$user->initials()" />

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                        <p class="text-gray-500 text-sm mb-1">{{ $user->email }}</p>
                    </div>
                </div>
                <flux:dropdown align="start" class="ml-auto">
                    <flux:button variant="primary" icon:trailing="ellipsis-vertical">Aksi</flux:button>

                    <flux:menu>
                        <flux:menu.item icon="pencil" x-on:click="$dispatch('modal-show', { name: 'update-user' }) ">
                            Edit
                        </flux:menu.item>

                        <flux:menu.separator />

                        <flux:menu.item x-on:click="$dispatch('modal-show', { name: 'delete-user' }) " variant="danger"
                            icon="trash" :disabled="auth()->user()->id === $user->id">Delete
                        </flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            </div>

            <div class="flex items-center space-x-2">
                <flux:text>Seksi: </flux:text>
                <flux:legend>{{ $user->section_label }}</flux:legend>
            </div>

            <div class="flex items-center space-x-2">
                <flux:text>No. Telp: </flux:text>
                <flux:legend>{{ $user->contact }}</flux:legend>
            </div>

            <div class="flex items-center space-x-2">
                <flux:text>Role: </flux:text>
                <flux:legend>{{ $user->roles->first()->name }}</flux:legend>
            </div>

            <div class="flex items-center space-x-2">
                <flux:text>Tanggal Dibuat: </flux:text>
                <flux:legend>{{ $user->created_at }}</flux:legend>
            </div>

            <div class="flex items-center space-x-2">
                <flux:text>Tanggal Diubah: </flux:text>
                <flux:legend>{{ $user->updated_at }}</flux:legend>
            </div>
        </div>

        @if ($user->roles->first()->name === 'user')
            {{-- <div class="p-6 border border-gray-200 rounded-lg overflow-hidden space-y-4">
                <flux:subheading size="lg" level="2">{{ __('Daftar Permohonan') }}</flux:subheading>
                @if ($this->requestsOfUser['informationSystemRequests']->isNotEmpty())
                <flux:legend>Sistem Informasi & Data</flux:legend>
                <ul class="space-y-2">
                    @foreach ($this->requestsOfUser['informationSystemRequests'] as $request)
                    <li class="p-4 border rounded-md shadow-sm bg-white">
                        <div class="font-medium text-gray-800">Title: {{ $request['title'] }}</div>
                        <div class="text-sm text-gray-400">Dibuat pada: </div>
                    </li>
                    @endforeach
                </ul>
                @endif

                @if ($this->requestsOfUser['publicRelationRequests']->isNotEmpty())
                <flux:legend>Kehumasan</flux:legend>
                <ul class="space-y-2">
                    @foreach ($this->requestsOfUser['publicRelationRequests'] as $request)
                    <li class="p-4 border rounded-md shadow-sm bg-white">
                        <div class="font-medium text-gray-800">Tema: {{ $request['theme'] }}</div>
                        <div></div>

                    </li>
                    @endforeach
                </ul>
                @endif
            </div> --}}
        @endif
    </div>

    <div class="max-w-screen-xl mb-6 ml-4 grid grid-cols-2 gap-4">
        <x-dashboard.small-chart icon="book-open" label="Total Permohonan" :data="$this->requestCount()"  />
        <x-dashboard.small-chart icon="chat-bubble-left" label="Total Diskusi" :data="$this->discussionCount()"  />
    </div>

    <flux:modal name="update-user" class="w-120 lg:max-w-lg">
        <form wire:submit="update" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Perbarui Data User') }}</flux:heading>
            </div>
            <flux:input wire:model="name" label="Nama" placeholder="Nama" required />
            <flux:input wire:model="email" label="Email" placeholder="Email" required />
            <flux:select wire:model="section" label="Seksi" placeholder="Seksi" required>
                @foreach ($this->getSections as $key => $section)
                    <option value="{{ $key }}">{{ $section }}</option>
                @endforeach
            </flux:select>

            <flux:input wire:model="contact" label="No. Telp" placeholder="No. Telp" required />
            <flux:select wire:model="role" label="Role" placeholder="Role" required>
                @foreach ($this->getRolesNames as $roles)
                    <option value="{{ $roles }}">{{ $roles }}</option>
                @endforeach
            </flux:select>

            <div class="flex justify-end mt-4">
                <flux:modal.close>
                    <flux:button variant="subtle">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">Perbarui</flux:button>
            </div>
        </form>
        {{-- <livewire:admin.user.update-user /> --}}
    </flux:modal>

    <flux:modal name="delete-user" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
        <form wire:submit="deleteUser" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Apakah Anda yakin ingin menghapus akun ini?') }}</flux:heading>

                <flux:subheading>
                    {{ __('Setelah akun dihapus, semua data akan dihapus secara permanen. Silahkan masukkan password Anda untuk memastikan Anda ingin menghapus akun ini.') }}
                </flux:subheading>
            </div>

            <flux:input wire:model="password" :label="__('Password')" type="password" x-ref="passwordInput"
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