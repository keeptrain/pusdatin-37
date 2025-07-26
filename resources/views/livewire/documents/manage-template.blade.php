<div>
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <flux:heading size="xl">Template Dokumen</flux:heading>
            <flux:subheading>Buat atau perbarui template dokumen yang akan di gunakan oleh pemohon
            </flux:subheading>
        </div>
        <flux:modal.trigger>
            <flux:button variant="primary" x-on:click="$dispatch('modal-show', { name: 'create-template' });"
                icon="plus" class="px-3 py-2 ">Template</flux:button>
        </flux:modal.trigger>
    </div>

    <!-- Template Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($templates as $template)
            <div wire:key="template-{{ $template->id }}" class="border rounded-lg hover:shadow-md transition-shadow">
                <!-- Header dengan dropdown dan status -->
                <div class="flex justify-between items-center p-3 border-b">
                    <span
                        class="text-xs px-2 py-1 rounded-full {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $template->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>

                    <div class="relative">
                        <flux:dropdown>
                            <flux:button variant="ghost" icon="adjustments-horizontal" class="size-12"></flux:button>

                            <flux:menu>
                                <flux:menu.item x-on:click="$wire.download({{ $template->id }})" icon="arrow-down-tray">
                                    Download
                                </flux:menu.item>

                                <flux:modal.trigger name="edit-template">
                                    <flux:menu.item x-on:click="$wire.edit({{ $template->id }})" icon="pencil-square">Edit
                                    </flux:menu.item>
                                </flux:modal.trigger>

                                <flux:menu.item x-on:click="$wire.delete({{ $template->id }})" icon="trash"
                                    variant="danger">
                                    Delete
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </div>
                </div>

                <!-- Body area-->
                <div class="p-4 space-y-6">
                    <div class="flex justify-center mb-3">
                        <flux:icon.document-text class="size-12" />
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-center mb-1">{{ $template->name }}</h3>
                        <p class="text-xs text-gray-500 text-center mb-2">{{ $template->part_number_label }}</p>
                    </div>

                    <div class="flex justify-between items-center text-xs text-gray-500 mt-3">
                        <span class="flex items-center gap-2">
                            <flux:icon.arrow-up-tray class="size-4" />{{ $template->created_at }}</span>
                        <span class="flex items-center gap-2">
                            <flux:icon.arrow-path class="size-4" />{{ $template->updated_at }}</span>
                    </div>
                </div>
            </div>
        @endforeach
        <x-modal.create-template />
        <x-modal.edit-template />
    </div>
</div>