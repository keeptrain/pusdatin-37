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
                            <flux:icon.sun class="size-10" />
                        </template>
                        <template x-if="isNight">
                            <flux:icon.moon class="size-10" x-if="isNight" />
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <x-user.hero-dashboard />

        <flux:separator class="mt-6" />

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-user.meeting-list :meetingList="$meetingList" :todayMeetingCount="$todayMeetingCount" />

            <x-user.dashboard.notifications-list />
        </div>

        {{-- <x-user.about-me /> --}}

        <flux:separator />

        <x-user.cara-kerja bg="bg-none" />
    </div>

</x-layouts.app>