<div x-data="{ createMeeting: false, selectedOption: '' }">
    <flux:button :href="route('letter.table')" icon="arrow-long-left" variant="subtle">Kembali ke Tabel</flux:button>

    <flux:heading size="xl" class="p-4">Meeting Permohonan Layanan</flux:heading>

    <x-letters.detail-layout overViewRoute="letter.detail" activityRoute="letter.activity" :id="$siRequestId">

        <div class="md:p-12 space-y-4">
            <flux:button @click="createMeeting = !createMeeting" class="w-full" icon="plus-circle">
                Rapat baru
            </flux:button>

            <!-- Form Rapat Baru -->
            <form wire:submit="createMeeting" x-show="createMeeting" x-transition.duration.300ms>
                <div class="mt-3 mb-3">
                    {{-- <flux:heading size="lg">Rapat baru</flux:heading> --}}
                    <p class="text-sm text-gray-600">Buat rapat baru berdasarkan opsi yang dipilih</p>
                </div>

                <div class="items-start grid grid-cols-2 gap-2">
                    <flux:menu.cards-with-icon wire:model.live="selectedOption" />
                </div>
            </form>

            <section class="items-start grid grid-rows-2 space-y-4 ">
                @forelse ($this->getMeeting as $key => $value)
                    <x-user.information-system.meeting-details-in-card :key="$key" :meeting="$value" />
                @empty
                    <!-- Empty Meeting -->
                    <div class="flex flex-col items-center p-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-4">
                            <flux:icon.x-mark />
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada meeting</h3>
                        <p class="text-center text-gray-500">di permohonan layanan ini</p>
                    </div>
                @endforelse

                {{-- <flux:modal name="result-meeting" focusable class="md:w-120" size="lg">
                    <form wire:submit="updateResultMeeting" class="space-y-4">
                        {{ $selectedResultKey }}
                        <flux:legend>Catat hasil meeting</flux:legend>
                        <flux:textarea wire:model="result" placeholder="Hasil dari meeting..." />
                        <div class="flex justify-end">
                            <flux:button variant="primary" type="submit">Simpan</flux:button>
                        </div>
                    </form>

                </flux:modal> --}}
            </section>
        </div>
    </x-letters.detail-layout>
</div>