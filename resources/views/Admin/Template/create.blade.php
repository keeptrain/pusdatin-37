<x-layouts.app :title="__('Dashboard')">
    <form action="{{ route('store.template') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="space-y-6">
            <flux:input id="name" name="name" label="Title" clearable />

            <flux:textarea id="description" name="description" label="Description" />

            <flux:legend>Upload document</flux:legend>
            <flux:input.file id="file_path" name="file_path" type="file" />

            <flux:fieldset class="space-y-2">
                <flux:legend>Part of document</flux:legend>
                <flux:description>Pilih sesuai part document yang ingin di pilih</flux:description>
                <flux:radio.group id="part_number" name="part_number"invalid>
                    <div class="flex gap-4 *:gap-x-2">
                        <flux:radio value="1" label="Sistem Informasi" />
                        <flux:radio value="2" label="Data" />
                        <flux:radio value="3" label="Data" />
                    </div>
                </flux:radio.group>
            </flux:fieldset>

            {{-- <x-letters.input-file-adapter title="Upload template" /> --}}

            <div class="flex justify-between">
                <flux:button href="{{ route('manage.templates') }}">Cancel</flux:button>
                <flux:button variant="primary" type="submit">Create</flux:button>
            </div>
        </div>

    </form>
</x-layouts.app>