<x-letters.layout legend="Upload Form">

    <form wire:submit="save" class="space-y-6 mb-6 ">
        <div class="border border-gray-200 rounded-lg p-4">
            <h3 class="text-md font-medium text-gray-700 mb-4 flex items-center">
                <span
                    class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 inline-flex items-center justify-center mr-2">1</span>
                Basic information
            </h3>

            <div class="space-y-6">
                <flux:input wire:model="title" label="Title" placeholder="Your title request service" clearable />

                <flux:input wire:model="responsible_person" label="Responsbile person" placeholder="NRK / Name"
                    clearable />

                <div class="grid grid-cols-2 gap-x-6">

                    <div>
                        <flux:input wire:model="reference_number" label="Reference number" placeholder="No./xx/xx/2025"
                            clearable />
                    </div>

                    <div>
                        <flux:select label="Section" placeholder="Choose section...">
                            <flux:select.option>Seksi A</flux:select.option>
                            <flux:select.option>Seksi B</flux:select.option>
                            <flux:select.option>Seksi C</flux:select.option>
                            <flux:select.option>Seksi D</flux:select.option>
                        </flux:select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Document Upload -->
        <div x-data="{
            activeUploads: 0,
            progress: 0,
            get uploading() {
                return this.activeUploads > 0;
            }
        }" x-on:livewire-upload-start="activeUploads++" x-on:livewire-upload-finish="activeUploads--"
            x-on:livewire-upload-error="activeUploads--" x-on:livewire-upload-cancel="activeUploads--"
            x-on:livewire-upload-progress="progress = $event.detail.progress">
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-md font-medium text-gray-700 mb-4 flex items-center">
                    <span
                        class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 inline-flex items-center justify-center mr-2">2</span>
                    Upload document
                </h3>

                <div class="space-y-6">
                    <!-- File Upload -->
                    <x-letters.input-file-adapter title="Part 1" model="files.0" />
                    <x-letters.input-file-adapter title="Part 2" model="files.1" />
                    <x-letters.input-file-adapter title="Part 3" model="files.2" />

                </div>
            </div>

            <div class="flex flex-row justify-between mt-4">
                <flux:button type="button" :href="route('letter')" wire:navigate>
                    {{ __('Cancel') }}
                </flux:button>

                <flux:button type="submit" variant="primary" x-bind:disabled="uploading">
                    {{ __('Create') }}
                </flux:button>
            </div>
        </div>
    </form>

</x-letters.layout>