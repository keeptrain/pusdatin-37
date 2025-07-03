<x-layouts.app :title="__('Dashboard')">
    <!-- Welcome Section -->
    <div x-data="{
        greeting: '',
        currentDate: '',
        isNight: false,
        updateTime() {
            const now = new Date();
            this.currentDate = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long'
            });
            const hour = now.getHours();
            if (hour >= 5 && hour < 12) {
                this.greeting = 'Selamat pagi';
                this.isNight = false; // Set to false during the day
            } else if (hour >= 12 && hour < 18)
                this.greeting = 'Selamat siang';
            else {
                this.greeting = 'Selamat malam';
                this.isNight = true;  // Set to true during the night
            }
        }
    }" x-init="
        updateTime(); // Initialize the time and greeting
    " class="max-w-7xl mx-auto px-2" class="mb-8">
        <div class="bg-gradient-to-l from-zinc-600 to-blue-900 rounded-xl p-6 text-white shadow-sm">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <flux:heading size="xl" level="1" class="text-white">
                        <span x-text="`${greeting}, {{ auth()->user()->name }}`"></span>
                    </flux:heading>
                    <flux:text size="lg" class="text-gray-300" x-text="`Ini hari ${currentDate}`">
                    </flux:text>
                </div>
                <div class="hidden md:block">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                        <!-- Conditionally render sun or moon icon -->
                        <template x-if="!isNight">
                            <x-lucide-sun class="size-10" />
                        </template>
                        <template x-if="isNight">
                            <x-lucide-moon class="size-10" />
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <x-user.hero-dashboard />

        <flux:separator class="mt-6" />

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            {{-- {{ $meetingList }} --}}
            <x-user.meeting-list :meetingList="$meetingList" :todayMeetingCount="$todayMeetingCount" />
        </div>

        <flux:separator />

        <div x-data="{ createDiscussion: false }" class="p-4 border-2 rounded-lg mt-6 ">
            <div class="space-y-2">
                <flux:heading size="xl" class="text-testing-100">Apakah kamu mempunyai kendala saat menggunakan layanan
                    Pusdatin?</flux:heading>
                <flux:button @click="createDiscussion =! createDiscussion" icon="plus">Buat forum diskusi</flux:button>

                <form x-show="createDiscussion" class="mt-6">
                    <div class="grid grid-cols-2 items-start gap-4 md:gap-6">
                        <flux:input label="Topic" placeholder="Topik yang ingin kamu diskusikan" />
                        <flux:textarea label="Deskripsi" placeholder="Deskripsi masalah yang ingin kamu diskusikan"
                            rows="2" />
                    </div>

                    <div class="flex justify-end mt-6">
                        <flux:button type="submit" variant="primary">Buat</flux:button>
                    </div>
                </form>
            </div>

            <div class="border border-lg border-l mt-6">
                {{-- <flux:heading size="xl" class="text-testing-100">Forum Diskusi</flux:heading> --}}
                <!-- Empty State -->
                <div class="text-center py-6">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada diskusi</h3>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>