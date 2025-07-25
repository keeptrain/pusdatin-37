<div x-data="{ createMeeting: false, selectedOption: '' }" x-cloak>
    <flux:button :href="route('is.index')" icon="arrow-long-left" variant="subtle">Kembali ke Tabel</flux:button>

    <flux:heading size="lg" class="p-4">Meeting Permohonan Layanan</flux:heading>

    <x-layouts.requests.show overViewRoute="is.show" activityRoute="is.activity" :id="$systemRequestId">
        @unlessrole('head_verifier')
        <div class="flex justify-between mb-4 mt-4">
            <flux:button size="sm" variant="primary" @click="createMeeting = !createMeeting" icon="plus">
                Rapat baru
            </flux:button>
        </div>

        <!-- Form Rapat Baru -->
        <form wire:submit="create" x-show="createMeeting" x-transition.duration.300ms>
            <div class="mt-3 mb-2">
                <p class="text-sm text-gray-600">Buat rapat baru berdasarkan opsi yang dipilih</p>
            </div>

            <div class="items-start space-y-4 lg:grid lg:grid-cols-2 lg:gap-2 lg:space-y-4">
                <x-menu.information-system.meeting-options wire:model.live="selectedOption" />
            </div>
        </form>
        @endunlessrole

        <div class="space-y-4">
            @forelse ($this->getMeetings as $meeting)
                <x-menu.information-system.meeting-adapter wire:key="{{ $meeting['id'] }}"
                    :systemRequestId="$systemRequestId" :meeting="$meeting" />
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
        </div>
    </x-layouts.requests.show>
</div>