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

        {{-- Hero Dashboard --}}
        <x-user.hero-dashboard />

        <flux:separator class="mt-6" />

        {{-- Meeting List --}}
        <div class="gap-4 mt-4">
            <x-user.meeting-list :meetingList="$meetingList" :todayMeetingCount="$todayMeetingCount" />
        </div>

        <flux:separator />

        {{-- Discussion List --}}
        <div x-data="{ showForm: false }" class="p-4 border dark:border-zinc-700 rounded-lg mt-6">
            <x-user.dashboard.discussion-forum :requests="$requests" />
        </div>
    </div>
</x-layouts.app>