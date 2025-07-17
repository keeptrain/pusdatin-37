<x-layouts.app :title="__('Dashboard')">
    <div x-data="{ greeting: '' }" x-init="const hour = new Date().getHours();
            if (hour >= 5 && hour < 12) {
                greeting = 'Good morning';
            } else if (hour >= 12 && hour < 18) {
                greeting = 'Good afternoon';
            } else {
                greeting = 'Good evening';
            }" class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div>
            <flux:heading size="xl" level="1" class="text-color-testing-100">
                <span x-text="`${greeting}, {{ auth()->user()->name }}`"></span>
            </flux:heading>
        </div>
        <flux:text class="text-base">Berikut berita terbaru hari ini</flux:text>

        <flux:separator variant="subtle" />

        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <!-- Total Service Requests Overview -->
            @hasrole('administrator')
            <x-dashboard.small-chart icon="user" :data="$totalUsers" label="Total user terdaftar" />
            @endhasrole

            @unlessrole('administrator')
            <x-dashboard.small-chart icon="folder" title="Permohonan Layanan" :data="$totalServices" :label="$label"
                :widthPercentage="$widthPercentage" />
            @endunlessrole

            <!-- Request Categories Distribution -->
            @hasanyrole('head_verifier')
            <div
                class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 flex flex-col">
                <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-200 mb-3">Kategori</h3>
                <div class="flex-1 flex flex-col justify-center space-y-1">
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full bg-blue-300 mr-2"></div>
                        <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Sistem Informasi</div>
                        <div class="font-medium text-neutral-800 dark:text-white">{{ $categoryPercentages['si'] }}%
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full bg-red-300 mr-2"></div>
                        <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Data</div>
                        <div class="font-medium text-neutral-800 dark:text-white">{{ $categoryPercentages['data'] }}%
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full bg-orange-300 mr-2"></div>
                        <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Kehumasan</div>
                        <div class="font-medium text-neutral-800 dark:text-white">{{ $categoryPercentages['pr'] }}%
                        </div>
                    </div>
                </div>
            </div>
            @endhasanyrole

            @hasanyrole('si_verifier|data_verifier')
            <div
                class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 flex flex-col">
                <x-dashboard.information-system-status-meter :statusCounts="$statusCounts" />
            </div>
            @endhasanyrole

            @hasanyrole('pr_verifier')
            <div
                class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 flex flex-col">
                <x-dashboard.public-relation-status-meter :statusCounts="$statusCounts" />
            </div>
            @endhasanyrole

            @hasanyrole('promkes_verifier')
            <div
                class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 flex flex-col">
                <x-dashboard.public-relation-status-meter :statusCounts="$statusCounts" />
            </div>
            @endhasanyrole

            @unlessrole('administrator')
            <x-dashboard.small-chart icon="star" title="" :data="$avarageRating" label="Rata-rata penilaian layanan"
                :widthPercentage="$widthPercentage" />
            @endunlessrole
        </div>
        @unlessrole('administrator')
        <div class="grid auto-rows-min gap-4 md:grid-cols-2 border rounded-2xl pl-2">
            <x-user.meeting-list :meetingList="$meetingList" :todayMeetingCount="$todayMeetingCount" />
        </div>
        @endunlessrole

        <!-- Chart area -->
        @pushOnce('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @endPushOnce
        @unlessrole('administrator')
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 max-h-85" x-cloak>
            <flux:heading size="lg" class="flex items-center gap-2 text-accent">
                <x-lucide-line-chart class="w-6 h-6" />
                Statistik Permohonan Layanan
            </flux:heading>
            @hasanyrole('head_verifier')
            <x-chart.head-verif :monthlyLetterData="$monthlyLetterData" />
            @endhasanyrole
            @hasanyrole('si_verifier')
            <x-chart.si-verif :monthlySiData="$monthlySiData" />
            @endhasanyrole
            @hasanyrole('data_verifier')
            <x-chart.data-verif :monthlyDataDiv="$monthlyDataDiv" />
            @endhasanyrole
            @hasanyrole('pr_verifier|promkes_verifier')
            <x-chart.pr-verif :monthlyLetterData="$monthlyLetterData" />
            @endhasanyrole
        </div>
        @endunlessrole
    </div>
</x-layouts.app>