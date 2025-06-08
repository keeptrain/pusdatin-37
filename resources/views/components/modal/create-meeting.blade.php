<flux:modal name="create-meeting-modal" focusable class="md:w-200" size="lg">
    <form wire:submit="createMeeting">
        <div class="p-6">
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-900 mb-1">Opsi Rapat</h2>
                <p class="text-sm text-gray-600">Pilih format rapat yang Anda inginkan</p>
            </div>

            <div class="grid grid-rows-1 md:grid-rows-2 gap-2">
                <!-- In-person Option -->
                <flux:menu.cards-with-icon wire:model="selectedOption" />
                @error('selectedOption')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror

                <!-- Selected option su mmary -->
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center mb-4">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                        <span class="text-sm font-medium text-gray-900">
                            Yang dipilih:
                            <span
                                x-text="selectedOption === 'in-person' ? 'Pertemuan langsung' : selectedOption === 'online-meet' ? 'Online Meeting' : ''"></span>
                        </span>
                    </div>
                    <section x-show="selectedOption === 'in-person'" class="mb-4">
                        <flux:textarea wire:model="meeting.location" label="Lokasi"
                            placeholder="Masukkan lokasi disini..." rows="1" />
                    </section>

                    <section x-show="selectedOption === 'online-meet'" class="mb-4">
                        <flux:textarea wire:model="meeting.link" label="Link" placeholder="Masukkan URL disini..."
                            rows="2" />
                    </section>

                    <section x-show="selectedOption != ''" class="space-y-4">
                        <flux:input wire:model="meeting.date" type="date" label="Tanggal" />
                        <div class="grid grid-cols-2 gap-4">
                            <flux:input wire:model="meeting.start" type="time" label="Mulai" />
                            <flux:input wire:model="meeting.end" type="time" label="Sampai" />
                        </div>
                    </section>
                    </p>
                </div>

                <!-- Form submission -->
                <div class="mt-6 flex justify-end">
                    <flux:button type="submit" variant="primary">
                        Buat
                    </flux:button>
                </div>
            </div>
        </div>
    </form>
</flux:modal>