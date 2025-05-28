<flux:modal x-data="{
    selectedOption: '',
    mode: 'create',
    meeting: {
        location: '',
        link: '',
        date: '',
        start: '',
        end: ''
    },
    viewMode() {
        this.mode = 'view';
    },
    editMode() {
        this.mode = 'edit';
    },
    createMode() {
        this.mode = 'create';
        this.resetForm();
    },
    resetForm() {
        this.meeting = {
            location: '',
            link: '',
            date: '',
            start: '',
            end: ''
        };
        this.selectedOption = '';
    }
}" name="view-meeting-modal" focusable class="md:w-200" size="lg" x-on:set-mode.window="mode = $event.detail.mode">
    <form wire:submit="saveMeeting">

        <!-- Header -->
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-1">
                <span
                    x-text="mode === 'create' ? 'Buat Meeting' : mode === 'edit' ? 'Edit Meeting' : 'Lihat Meeting'"></span>
            </h2>
            <p class="text-sm text-gray-600">Pilih format pertemuan yang diinginkan</p>
        </div>

        <!-- Meeting Options -->
        <div class="grid grid-rows-1 md:grid-rows-2 gap-2">
            <flux:menu.cards-with-icon wire:model="selectedOption" />

            @error('selectedOption')
                <span class="text-xs text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Selected Option Summary -->
        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center mb-4">
                <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                <span class="text-sm font-medium text-gray-900">
                    Yang dipilih:
                    <span
                        x-text="selectedOption === 'in-person' ? 'Pertemuan langsung' : selectedOption === 'online-meet' ? 'Online Meeting' : ''"></span>
                </span>
            </div>

            <!-- Location for In-Person -->
            <section x-show="selectedOption === 'in-person'" class="mb-4">
                <flux:textarea wire:model="meeting.location" label="Lokasi" placeholder="Masukkan lokasi disini..."
                    rows="1" x-bind:disabled="mode === 'view'" />
            </section>

            <!-- Link for Online Meeting -->
            <section x-show="selectedOption === 'online-meet'" class="mb-4">
                <flux:textarea wire:model="meeting.link" label="Link" placeholder="Masukkan URL disini..." rows="2"
                    x-bind:disabled="mode === 'view'" />
            </section>

            <!-- Date and Time -->
            <section x-show="selectedOption != ''" class="space-y-4">
                <flux:input wire:model="meeting.date" type="date" label="Tanggal" x-bind:disabled="mode === 'view'" />
                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="meeting.start" type="time" label="Mulai"
                        x-bind:disabled="mode === 'view'" />
                    <flux:input wire:model="meeting.end" type="time" label="Sampai" x-bind:disabled="mode === 'view'" />
                </div>
            </section>
        </div>

        <!-- Buttons -->
        <div class="mt-6 flex justify-end">
            <template x-if="mode !== 'view'">
                <flux:button type="submit" variant="primary">
                    Simpan
                </flux:button>
            </template>
            <template x-if="mode === 'view'">
                <flux:button @click="$dispatch('close')">Tutup</flux:button>
            </template>
        </div>
    </form>
</flux:modal>