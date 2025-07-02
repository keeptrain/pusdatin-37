<div class="lg:p-3">
    <!-- Header Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-6 py-4">
        <div class="flex justify-between items-center">
            <div>
                <flux:heading size="xl">Template Dokumen</flux:heading>
                <flux:subheading>Buat atau perbarui template dokumen yang akan di gunakan oleh pemohon
                </flux:subheading>
            </div>
            <flux:modal.trigger>
                <flux:button variant="primary" x-on:click="$dispatch('modal-show', { name: 'create-template' });"
                    class="px-3 py-2 ">Template baru</flux:button>
            </flux:modal.trigger>

        </div>
    </div>

    <!-- Template Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-4 py-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($templates as $template)
                <div class="shadow-sm overflow-hidden" wire:key="template-{{ $template->id }}">
                    <!-- Template Preview -->
                    <div class="template-preview h-40 p-6 flex items-center justify-center relative">
                        <div class="absolute top-4 right-4">
                            <flux:dropdown>
                                <flux:button variant="ghost" icon="adjustments-horizontal" class="size-12"></flux:button>

                                <flux:menu>
                                    <flux:menu.item wire:click="download({{ $template->id }})" icon="arrow-down-tray">
                                        Download</flux:menu.item>

                                    <flux:modal.trigger name="edit-template">
                                        <flux:menu.item wire:click="edit({{ $template->id }})" icon="pencil-square">Edit
                                        </flux:menu.item>
                                    </flux:modal.trigger>

                                    <flux:menu.item wire:click="delete({{ $template->id }})" icon="trash" variant="danger">
                                        Delete</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </div>
                        <div class="text-center mt-8">
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
                            <flux:legend size="lg">{{ $template->name }}</flux:legend>
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
                    </div>
                </div>
            @endforeach
        </div>
        <x-modal.create-template />
        <x-modal.edit-template />
    </div>
</div>