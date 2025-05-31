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
        <flux:text class="text-base">Here's what's new today</flux:text>

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
                        <div class="bg-zinc-700 h-2 rounded-full" style="width: {{ $widthPercentage }}%"></div>
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
        <div class=" rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 h-full" wire:ignore>
            <canvas id="monthlyLettersChart"></canvas>
            @push('scripts')
                <script>
                    // Global variable untuk chart
                    let monthlyChart = null;

                    function initMonthlyChart() {
                        // Data dari DashboardController
                        const monthlyData = @json($monthlyLetterData ?? ['months' => [], 'letterData' => [], 'prData' => []]);

                        // Cek apakah element ada
                        const chartElement = document.getElementById('monthlyLettersChart');
                        if (!chartElement) return;

                        // Destroy chart yang sudah ada
                        if (monthlyChart instanceof Chart) {
                            monthlyChart.destroy();
                        }

                        // Dapatkan context
                        const ctx = chartElement.getContext('2d');

                        // Dataset untuk chart
                        const letterGradient = ctx.createLinearGradient(0, 0, 0, 400);
                        letterGradient.addColorStop(0, 'rgba(31, 41, 55, 0.8)');
                        letterGradient.addColorStop(1, 'rgba(31, 41, 55, 0.2)');

                        const prGradient = ctx.createLinearGradient(0, 0, 0, 400);
                        prGradient.addColorStop(0, 'rgba(249, 115, 22, 0.8)');
                        prGradient.addColorStop(1, 'rgba(249, 115, 22, 0.2)');

                        monthlyChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: monthlyData.months,
                                datasets: [
                                    {
                                        label: 'Sistem Informasi Dan Data',
                                        data: monthlyData.letterData,
                                        backgroundColor: letterGradient,
                                        borderColor: '#1f2937',
                                        borderWidth: 2,
                                        borderRadius: 8,
                                        borderSkipped: false,
                                    },
                                    {
                                        label: 'Humas',
                                        data: monthlyData.prData,
                                        backgroundColor: prGradient,
                                        borderColor: '#f97316',
                                        borderWidth: 2,
                                        borderRadius: 8,
                                        borderSkipped: false,
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top',
                                        labels: {
                                            usePointStyle: true,
                                            padding: 20,
                                            font: {
                                                size: 12,
                                                weight: '500'
                                            }
                                        }
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(17, 24, 39, 0.9)',
                                        titleColor: '#ffffff',
                                        bodyColor: '#ffffff',
                                        borderColor: '#374151',
                                        borderWidth: 1,
                                        cornerRadius: 8,
                                        displayColors: true,
                                        callbacks: {
                                            title: function (context) {
                                                return context[0].label + ' {{ date("Y") }}';
                                            },
                                            label: function (context) {
                                                return context.dataset.label + ': ' + context.parsed.y;
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: {
                                            color: 'rgba(156, 163, 175, 0.2)',
                                            drawBorder: false
                                        },
                                        ticks: {
                                            color: '#6b7280',
                                            font: {
                                                size: 12
                                            }
                                        }
                                    },
                                    x: {
                                        grid: {
                                            display: false
                                        },
                                        ticks: {
                                            color: '#6b7280',
                                            font: {
                                                size: 12,
                                                weight: '500'
                                            }
                                        }
                                    }
                                },
                                animation: {
                                    duration: 2000,
                                    easing: 'easeOutQuart'
                                },
                                interaction: {
                                    intersect: false,
                                    mode: 'index'
                                }
                            }
                        });
                    }


                    document.addEventListener('DOMContentLoaded', function () {
                        initMonthlyChart();
                    });


                    document.addEventListener('livewire:navigated', function () {
                        setTimeout(initMonthlyChart, 100);
                    });


                    document.addEventListener('livewire:updated', function () {
                        setTimeout(initMonthlyChart, 100);
                    });

                    // Backup untuk window load
                    window.addEventListener('load', function () {
                        if (!monthlyChart) {
                            initMonthlyChart();
                        }
                    });
                </script>
            @endpush
        </div>
        @endhasanyrole
        <!-- bar chart area -->
    </div>

</x-layouts.app>