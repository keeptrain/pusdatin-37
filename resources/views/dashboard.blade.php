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

            <x-dashboard.small-chart icon="star" title="" :data="'4.5 / 5'" label="Rata-rata penilaian layanan"
                :widthPercentage="$widthPercentage" />

            {{-- <div
                class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 flex flex-col">
                <flux:heading size="lg">Penilaian Layanan</flux:heading>
                <flux:avatar icon="star" size="xl" color="auto" />
                <x-rating-emoticon />

            </div> --}}
        </div>
        <!-- bar chart area -->
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @endpush
        @hasanyrole('head_verifier')
        <div class=" rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 max-h-80" wire:ignore>
            <flux:heading>Chart Permohonan</flux:heading>
            <canvas id="monthlyLettersChart"></canvas>
            @push('scripts')
                <script>
                    // Global variable untuk chart
                    let monthlyChart = null;

                    function initMonthlyChart() {
                        // Data dari DashboardController
                        const monthlyData = @json($monthlyLetterData ?? ['months' => [], 'informationSystem' => [], 'publicRelation' => []]);

                        // Cek apakah element ada
                        const chartElement = document.getElementById('monthlyLettersChart');
                        if (!chartElement) return;

                        // Destroy chart yang sudah ada
                        if (monthlyChart instanceof Chart) {
                            monthlyChart.destroy();
                        }

                        // Dapatkan context
                        const ctx = chartElement.getContext('2d');


                        monthlyChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: monthlyData.months,
                                datasets: [{
                                    label: 'Sistem Informasi',
                                    data: monthlyData.informationSystem,
                                    backgroundColor: '#2b7fff',
                                    borderSkipped: false,
                                },
                                {
                                    label: 'Data',
                                    data: monthlyData.data,
                                    backgroundColor: '#00786f',
                                    borderSkipped: false,
                                },
                                {
                                    label: 'Humas',
                                    data: monthlyData.publicRelation,
                                    backgroundColor: '#ca3500',
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
                                        borderColor: '',
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
                </script>
            @endpush
        </div>
        @endhasanyrole
        @hasanyrole('si_verifier')
        <div class=" rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 max-h-72" wire:ignore>
            <flux:heading>Chart Permohonan</flux:heading>
            <canvas id="siVerifierChart"></canvas>
            @push('scripts')
                <script>
                    // Simpan instansi chart agar bisa di‐destroy sebelum re‐init
                    let siChart = null;

                    function initSiVerifierChart() {
                        const raw = @json($monthlySiData);

                        const canvas = document.getElementById('siVerifierChart');
                        if (!canvas) return;

                        // Jika sebelumnya sudah ada chart, destroy dulu
                        if (siChart instanceof Chart) {
                            siChart.destroy();
                        }

                        const ctx = canvas.getContext('2d');

                        const letterGradient = ctx.createLinearGradient(0, 0, 0, 400);
                        letterGradient.addColorStop(0, 'rgba(31, 41, 55, 0.8)'); // #1F2937 @ 80%
                        letterGradient.addColorStop(1, 'rgba(31, 41, 55, 0.2)'); // #1F2937 @ 20%

                        siChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: raw.months,
                                datasets: [{
                                    label: 'Sistem Informasi',
                                    data: raw.letterData,
                                    backgroundColor: '#00786f',
                                    borderSkipped: false,
                                }]
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
                                            title: ctx => ctx[0].label + ' ' + new Date().getFullYear(),
                                            label: ctx => ctx.dataset.label + ': ' + ctx.parsed.y
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

                    document.addEventListener('DOMContentLoaded', initSiVerifierChart);
                    document.addEventListener('livewire:navigated', () => setTimeout(initSiVerifierChart, 100));
                </script>
            @endpush
        </div>
        @endhasanyrole
        @hasanyrole('data_verifier')
        <div class=" rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 h-64" wire:ignore>
            <flux:heading>Chart Permohonan</flux:heading>
            <canvas id="dataVerifierChart"></canvas>
            @push('scripts')
                <script>
                    // Simpan instansi chart agar bisa di‐destroy sebelum re‐init
                    let dataDivChart = null;

                    function initDataVerifierChart() {
                        const dataDiv = @json($monthlyDataDiv);

                        const canvas = document.getElementById('dataVerifierChart');
                        if (!canvas) return;

                        // Jika sebelumnya sudah ada chart, destroy dulu
                        if (dataDivChart instanceof Chart) {
                            dataDivChart.destroy();
                        }

                        const ctx = canvas.getContext('2d');

                        // const letterGradient = ctx.createLinearGradient(0, 0, 0, 400);
                        // letterGradient.addColorStop(0, 'rgba(31, 41, 55, 0.8)'); // #1F2937 @ 80%
                        // letterGradient.addColorStop(1, 'rgba(31, 41, 55, 0.2)'); // #1F2937 @ 20%

                        dataDivChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: dataDiv.months,
                                datasets: [{
                                    label: 'Data',
                                    data: dataDiv.letterData,
                                    backgroundColor: '#00786f',
                                    borderSkipped: false,
                                }]
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
                                            title: ctx => ctx[0].label + ' ' + new Date().getFullYear(),
                                            label: ctx => ctx.dataset.label + ': ' + ctx.parsed.y
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

                    document.addEventListener('DOMContentLoaded', initDataVerifierChart);
                    document.addEventListener('livewire:navigated', () => setTimeout(initDataVerifierChart, 100));
                </script>
            @endpush
        </div>
        @endhasanyrole
        @hasanyrole('pr_verifier|promkes_verifier')
        <div class=" rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 h-64" wire:ignore>
            <flux:heading>Chart Permohonan</flux:heading>
            <canvas id="prVerifierChart"></canvas>
            @push('scripts')
                <script>
                    let prChart = null;

                    function initPrVerifierChart() {
                        // Ambil semua months/letterData/prData
                        const raw = @json($monthlyLetterData);

                        const canvas = document.getElementById('prVerifierChart');
                        if (!canvas) return;
                        if (prChart instanceof Chart) prChart.destroy();

                        const ctx = canvas.getContext('2d');

                        prChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: raw.months,
                                datasets: [{
                                    label: 'Humas',
                                    data: raw.publicRelation,
                                    backgroundColor: '#F97316', // Tailwind orange-600
                                    borderSkipped: false,
                                }]
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
                                            title: ctx => ctx[0].label + ' ' + new Date().getFullYear(),
                                            label: ctx => ctx.dataset.label + ': ' + ctx.parsed.y
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
                                            color: '#6B7280',
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
                                            color: '#6B7280',
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
                    document.addEventListener('DOMContentLoaded', initPrVerifierChart);
                    document.addEventListener('livewire:navigated', () => setTimeout(initPrVerifierChart, 100));
                </script>
            @endpush
        </div>
        @endhasanyrole

        <!-- bar chart area -->
    </div>

</x-layouts.app>