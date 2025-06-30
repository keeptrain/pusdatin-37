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
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 flex flex-col">
                <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-200 mb-3">Permohonan Layanan</h3>
                <p class="flex text-3xl font-bold text-neutral-800 dark:text-white mb-1">{{ $totalServices }}
                <p class="text-sm font-medium text-neutral-800 dark:text-white mb-1">{{ $label }}
                </p>
                </p>

                {{-- <div class="text-sm font-medium text-emerald-600 dark:text-emerald-500 flex items-center mb-5">
                    <flux:icon.arrow-trending-up class="size-5 mr-2" />
                    12% from last month
                </div> --}}
                <div class="mt-auto">
                    {{-- <div class="bg-neutral-100 dark:bg-neutral-800 rounded-full h-2 mb-2">
                        <div class="bg-zinc-700 h-2 rounded-full" style="width: {{ $widthPercentage }}%">
                </div>
            </div> --}}
            {{-- <div class="flex justify-between text-xs text-neutral-500 dark:text-neutral-400">
                        <span>{{ $statusCounts['completed'] }} Selesai</span>
            @hasrole('head_verifier')
            <span>{{ $statusCounts['pending'] }} {{ $label }}</span>
            @elserole('si_verifier|data_verifier')
            <span>{{ $statusCounts['disposition'] }} {{ $label }}</span>
            @elserole('pr_verifier')
            <span>{{ $statusCounts['pusdatinProcess'] }} {{ $label }}</span>
            @elserole('promkes_verifier')
            <span>{{ $statusCounts['pusdatinProcess'] }} {{ $label }}</span>
            @endhasrole
        </div> --}}
    </div>
    </div>

    <!-- Request Categories Distribution -->
    @hasanyrole('head_verifier')
    <div
        class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 flex flex-col">
        <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-200 mb-3">Kategori</h3>
        <div class="flex-1 flex flex-col justify-center space-y-3">
            <div class="flex items-center">
                <div class="w-2 h-2 rounded-full bg-blue-500 mr-2"></div>
                <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Sistem Informasi</div>
                <div class="font-medium text-neutral-800 dark:text-white">{{ $categoryPercentages['si'] }}%
                </div>
            </div>
            <div class="flex items-center">
                <div class="w-2 h-2 rounded-full bg-teal-700 mr-2"></div>
                <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Data</div>
                <div class="font-medium text-neutral-800 dark:text-white">{{ $categoryPercentages['data'] }}%
                </div>
            </div>
            <div class="flex items-center">
                <div class="w-2 h-2 rounded-full bg-orange-700 mr-2"></div>
                <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Kehumasan</div>
                <div class="font-medium text-neutral-800 dark:text-white">{{ $categoryPercentages['pr'] }}%
                </div>
            </div>
        </div>
    </div>
    {{-- <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 flex flex-col">
                <x-dashboard.head-verifier-status-meter :statusCounts="$statusCounts" />
            </div> --}}
    @endhasanyrole

    @hasanyrole('si_verifier|data_verifier')
    <div
        class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 flex flex-col">
        <x-dashboard.information-system-status-meter :statusCounts="$statusCounts" />
    </div>
    @endhasanyrole

    @hasanyrole('pr_verifier')
    <div
        class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 flex flex-col">
        <x-dashboard.public-relation-status-meter :statusCounts="$statusCounts" />
    </div>
    @endhasanyrole

    @hasanyrole('promkes_verifier')
    <div
        class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 flex flex-col">
        <x-dashboard.public-relation-status-meter :statusCounts="$statusCounts" />
    </div>
    @endhasanyrole

    {{-- <!-- Response Time Metrics -->
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 flex flex-col">
                <x-dashboard.information-system-status-meter :siStatusCounts="$siStatusCounts" />
            </div> --}}
    </div>
    <!-- bar chart area -->
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush
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

    <!-- bar chart area -->
    </div>

</x-layouts.app>