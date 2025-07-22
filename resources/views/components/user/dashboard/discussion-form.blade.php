<form wire:submit="create"
    x-init="$wire.on('discussion-created', () => { setTimeout(() => { showForm = false; }, 500) })" x-show="showForm"
    class="lg:ml-12 space-y-6" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-4"
    x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-4" wire:ignore.self>

    <div x-data="{ discussionType: '' }" class="grid grid-cols-1 md:grid-cols-2 items-start gap-4 md:gap-6 mt-4">
        <!-- Select untuk pertanyaan pertama -->
        <flux:select wire:model="form.discussableType" label="Terkait dengan layanan yang di ajukan?"
            placeholder="Pilih Kategori" x-model="discussionType">
            <flux:select.option value="yes">Ya</flux:select.option>
            <flux:select.option value="no">Tidak, selain itu</flux:select.option>
        </flux:select>

        <!-- Bagian yang hanya muncul jika discussionType === 'yes' -->
        <div x-show="discussionType === 'yes'">
            <flux:select wire:model="form.discussableId" label="Layanan yang mana?" placeholder="Pilih layanan terkait">
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

    <div x-data="{ 
    uploading: false, 
    progress: 0
}" x-on:livewire-upload-start="uploading = true; progress = 0"
        x-on:livewire-upload-finish="uploading = false; progress = 0"
        x-on:livewire-upload-error="uploading = false; progress = 0"
        x-on:livewire-upload-progress="progress = $event.detail.progress">
        <x-layouts.form.input-multiple-file :form="$form" />
    </div>

    <flux:textarea wire:model="form.body" label="Deskripsi" placeholder="Deskripsikan hal yang ingin kamu diskusikan..."
        rows="2" />

    <div class="flex justify-end space-x-2">
        <flux:button type="button" variant="subtle" x-on:click="showForm = !showForm">Batal
        </flux:button>
        <flux:button type="submit" variant="primary" icon="plus">Buat</flux:button>
    </div>
</form>