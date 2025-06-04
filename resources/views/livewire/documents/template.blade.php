<div class="lg:p-3">
    <!-- Header Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-6 py-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Template Dokumen</h1>
                <p class="mt-2 text-gray-600">Buat atau perbarui template dokumen yang akan di gunakan oleh pemohon
                </p>
            </div>
            <flux:modal.trigger>
                <flux:button variant="primary" x-on:click="$dispatch('modal-show', { name: 'create-template-modal' });"
                    class="px-3 py-2">Template baru</flux:button>
            </flux:modal.trigger>

        </div>
    </div>

    <!-- Template Grid -->
    <div x-data="{ selectedTemplate: null, name: $wire.name  }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-4 py-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($templates as $template)
                <div class="bg-white rounded-xl shadow-sm card-hover overflow-hidden">
                    <!-- Template Preview -->
                    <div class="template-preview h-48 p-6 flex items-center justify-center relative">
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"></span>
                        </div>
                        <div class="text-center ">
                            <flux:icon.document class="size-12" />
                        </div>

                        <!-- Active Badge -->
                        <div class="absolute top-4 left-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                                {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                <span
                                    class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $template->is_active ? 'bg-green-400' : 'bg-gray-400' }}"></span>
                                {{ $template->is_active ? 'Aktif' : 'Tidak aktif' }}
                            </span>
                        </div>
                    </div>

                    <!-- Template Info -->
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-3">
                            <flux:heading size="lg">{{ $template->name }}</flux:heading>
                        </div>

                        <!-- Template Stats -->
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <p class="text-xs ">{{ $template->part_number_label }}</p>
                            <div class="flex items-center space-x-2">
                                {{-- <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10">
                                        </path>
                                    </svg>
                                </div> --}}
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ $template->updated_at }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-3">
                            <flux:modal.trigger>
                                <flux:button
                                    x-on:click="$dispatch('modal-show', { name: 'edit-template-modal' }); selectedTemplate = {{ json_encode($template) }};"
                                    class="w-full mr-3">Edit</flux:button>
                            </flux:modal.trigger>

                            <flux:button wire:click="download({{ $template->id }})" icon="arrow-down-tray" type="submit" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <flux:modal name="create-template-modal" focusable class="md:w-120" size="lg">
            <form wire:submit="save" class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ __('Buat template') }}
                    </flux:heading>
                </div>
                <flux:input wire:model="name" name="name" label="Nama template" />

                {{-- <flux:radio.group wire:model="isActive" label="Kondisi template">
                    <flux:radio name="is_active" value="1" label="Aktif"
                        description="Bisa di download oleh pemohon pada form." checked />
                    <flux:radio name="is_active" value="0" label="Tidak aktif"
                        description="Tidak bisa di download oleh pemohon pada form." />
                </flux:radio.group> --}}
                <flux:select wire:model="partNumber" label="Bagian template" placeholder="Pilih bagian...">
                    <flux:select.option value="1">SPBE</flux:select.option>
                    <flux:select.option value="2">SOP</flux:select.option>
                    <flux:select.option value="3">Pemanfaatan Aplikasi</flux:select.option>
                    <flux:select.option value="4">RFC</flux:select.option>
                    <flux:select.option value="5">NDA</flux:select.option>
                </flux:select>
                <flux:input wire:model="file" type="file" name="file" label="File template" />
                <div class="flex justify-end space-x-2">
                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </flux:modal.close>

                    <flux:button variant="primary" type="submit">{{ __('Buat') }}</flux:button>
                </div>
            </form>
        </flux:modal>
        <flux:modal name="edit-template-modal" focusable class="md:w-120" size="lg">
            <template x-if="selectedTemplate">
                <form wire:submit="update(selectedTemplate.id)" class="space-y-6">
                    <div>
                        <flux:heading size="lg">
                            {{ __('Edit template') }}
                        </flux:heading>
                    </div>
                    <flux:input x-model="selectedTemplate.name" wire:model="name" name="name" label="Nama" />
                    <flux:radio.group x-model="selectedTemplate.is_active" label="Kondisi template">
                        <flux:radio name="is_active" value="1" label="Aktif"
                            description="Bisa di download oleh pemohon pada form." checked />
                        <flux:radio name="is_active" value="0" label="Tidak aktif"
                            description="Tidak bisa di download oleh pemohon pada form." />
                    </flux:radio.group>
                    {{--
                    <flux:input x-model="selectedTemplate.part_number_label" label="Bagian part" /> --}}
                    <flux:input wire:model="file" type="file" name="file" label="Ubah file template" />
                    <div class="flex justify-end space-x-2">
                        <flux:modal.close>
                            <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                        </flux:modal.close>

                        <flux:button variant="primary" type="submit">{{ __('Ubah') }}</flux:button>
                    </div>
                </form>
            </template>
        </flux:modal>

    </div>

</div>