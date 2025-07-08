<div class="lg:p-3">
    @unlessrole('user')
    <div class="flex items-center space-x-2">
        <flux:heading size="xl" class="">Forum Diskusi</flux:heading>
    </div>
    <flux:heading size="lg" level="2" class="mb-6">{{ __('Daftar diskusi dari para pemohon.') }}</flux:heading>

    <div class="flex justify-between space-x-2">
        <flux:input wire:model.blur="search" size="sm" icon="magnifying-glass" placeholder="Cari diskusi..." />

        <flux:button @click="$dispatch('modal-show', { name: 'filter-discussion-modal' });" size="sm" icon="eye">
            Filter</flux:button>
    </div>

    <flux:modal name="filter-discussion-modal" class="w-full max-w-md">
        <form wire:submit="refreshPage" class="space-y-4">
            <flux:legend>Filter Diskusi</flux:legend>

            <flux:select wire:model="discussableType" label="Kategori diskusi" placeholder="Pilih kategori">
                <option value="yes">Terkait permohonan</option>
                <option value="no">Tidak terkait</option>
            </flux:select>

            <flux:radio.group wire:model="isClosed" label="Status diskusi">
                <flux:radio label="Telah selesai" value="completed" />
                <flux:radio label="Belum selesai" value="ongoing" />
            </flux:radio.group>

            <div class="flex justify-end">
                <flux:button type="submit" size="sm">Terapkan</flux:button>
            </div>
        </form>
    </flux:modal>
    @endunlessrole

    @role('user')
    <!-- Discussion Form -->
    <form wire:submit="create" x-show="createDiscussion" class="lg:ml-9 space-y-6"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-4"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-4">

        <div x-data="{ discussionType: '' }" class="grid grid-cols-1 md:grid-cols-2 items-start gap-4 md:gap-6">
            <!-- Select untuk pertanyaan pertama -->
            <flux:select wire:model="form.discussableType" label="Terkait dengan layanan yang di ajukan?"
                placeholder="Pilih Kategori" x-model="discussionType">
                <flux:select.option value="yes">Ya</flux:select.option>
                <flux:select.option value="no">Tidak, selain itu</flux:select.option>
            </flux:select>

            <!-- Bagian yang hanya muncul jika discussionType === 'yes' -->
            <div x-show="discussionType === 'yes'">
                <flux:select wire:model="form.discussableId" label="Layanan yang mana?"
                    placeholder="Pilih layanan terkait">
                    @foreach ($requests as $request)
                        <flux:select.option value="{{ $request['type'] }}:{{ $request['id'] }}"> {{ $request['type'] }} -
                            {{ $request['label'] }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <div x-show="discussionType === 'no'">
                <flux:radio.group wire:model="form.kasatpel" label="Kasatpel terkait">
                    <div class="flex items-center space-x-2">
                        <flux:radio value="si" label="Sistem Informasi" />
                        <flux:radio value="data" label="Data" />
                        <flux:radio value="pr" label="Kehumasan" />
                    </div>
                </flux:radio.group>
            </div>
        </div>

        <flux:textarea wire:model="form.body" label="Deskripsi"
            placeholder="Deskripsikan masalah yang ingin kamu diskusikan..." rows="2" />

        <div class="flex justify-end space-x-2">
            <flux:button type="button" variant="subtle" @click="createDiscussion = !createDiscussion">Batal
            </flux:button>
            <flux:button type="submit" variant="primary" icon="plus">Buat</flux:button>
        </div>
    </form>
    @endrole

    <div class="space-y-4 mt-6">
        @forelse ($discussions as $discussion)
            <x-discussions.list :discussion="$discussion" />
        @empty
            <!-- Empty State -->
            <div x-show="!createDiscussion" class="px-8 py-8 text-center"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95">

                <div class="max-w-md mx-auto">
                    <!-- Illustration -->
                    <div class="relative mb-6">
                        <div
                            class="w-20 h-20 mx-auto bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mb-4 shadow-inner">
                            <x-lucide-message-circle class="size-10 text-blue-500" />
                        </div>
                        <!-- Floating elements -->
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-blue-400 rounded-full animate-bounce"></div>
                        <div class="absolute -bottom-2 -left-2 w-4 h-4 bg-blue-800 rounded-full animate-bounce"
                            style="animation-delay: 0.2s"></div>
                    </div>

                    <flux:heading size="lg">
                        Belum ada diskusi
                    </flux:heading>
                </div>
            </div>
        @endforelse
        {{ $discussions->links() }}
    </div>


</div>