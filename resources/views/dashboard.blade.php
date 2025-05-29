<?php

$completed = $siStatusCounts['approvedKapusdatin'] ?? 0;
$totalPending = $siStatusCounts['pending'] ?? 0;

// Hitung total layanan yang relevan untuk persentase (Selesai + Masuk)
$totalRelevantServices = $completed + $totalPending;

$widthPercentage = 0;
if ($totalRelevantServices > 0) {
    $widthPercentage = ($completed / $totalRelevantServices) * 100;
}

$widthPercentage = round($widthPercentage);



?>
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
                <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-200 mb-3">Service Requests</h3>
                <div class="text-3xl font-bold text-neutral-800 dark:text-white mb-1">{{ $totalServices }}</div>
                <div class="text-sm font-medium text-emerald-600 dark:text-emerald-500 flex items-center mb-5">
                    <flux:icon.arrow-trending-up class="size-5 mr-2" />
                    12% from last month
                </div>
                <div class="mt-auto">
                    <div class="bg-neutral-100 dark:bg-neutral-800 rounded-full h-2 mb-2">
                        <div class="bg-zinc-700 h-2 rounded-full" style="width: {{ $widthPercentage }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-neutral-500 dark:text-neutral-400">
                        <span>{{ $siStatusCounts['approvedKapusdatin'] }} Selesai</span>
                        <span>{{ $siStatusCounts['pending'] }} Permohonan Masuk</span>
                    </div>
                </div>
            </div>

            <!-- Request Categories Distribution -->
            @hasanyrole('head_verifier')
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 flex flex-col">
                <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-200 mb-3">Categories</h3>
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
            @endhasanyrole

            @hasanyrole('si_verifier|data_verifier')
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 flex flex-col">
                <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-200 mb-3">Status</h3>
                <div class="flex-1 flex flex-col justify-center space-y-3">
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></div>
                        <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Permohonan Masuk</div>
                        <div class="font-medium text-neutral-800 dark:text-white">{{ $siStatusCounts['pending'] }}
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></div>
                        <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Disposisi</div>
                        <div class="font-medium text-neutral-800 dark:text-white">{{ $siStatusCounts['disposition'] }}
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full bg-teal-700 mr-2"></div>
                        <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Proses</div>
                        <div class="font-medium text-neutral-800 dark:text-white">{{ $siStatusCounts['process'] }}
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full bg-teal-700 mr-2"></div>
                        <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Revisi</div>
                        <div class="font-medium text-neutral-800 dark:text-white">{{ $siStatusCounts['replied'] }}
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full bg-orange-700 mr-2"></div>
                        <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Disetujui Kasatpel</div>
                        <div class="font-medium text-neutral-800 dark:text-white">
                            {{ $siStatusCounts['approvedKasatpel'] }}
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full bg-orange-700 mr-2"></div>
                        <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Disetujui Kapusdatin</div>
                        <div class="font-medium text-neutral-800 dark:text-white">
                            {{ $siStatusCounts['approvedKapusdatin'] }}
                        </div>
                    </div>
                </div>
            </div>
            @endhasanyrole

            @hasanyrole('pr_verifier')
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 flex flex-col">
                <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-200 mb-3">Status</h3>
                <div class="flex-1 flex flex-col justify-center space-y-3">
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></div>
                        <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Permohonan Masuk</div>
                        <div class="font-medium text-neutral-800 dark:text-white">{{ $siStatusCounts['pending'] }}%
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></div>
                        <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Antrean Promkes</div>
                        <div class="font-medium text-neutral-800 dark:text-white">{{ $categoryPercentages['si'] }}%
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full bg-teal-700 mr-2"></div>
                        <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Kurasi Promkes</div>
                        <div class="font-medium text-neutral-800 dark:text-white">{{ $categoryPercentages['data'] }}%
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full bg-orange-700 mr-2"></div>
                        <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Antrean Pusdatin</div>
                        <div class="font-medium text-neutral-800 dark:text-white">{{ $categoryPercentages['pr'] }}%
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full bg-orange-700 mr-2"></div>
                        <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Proses Pusdatin</div>
                        <div class="font-medium text-neutral-800 dark:text-white">{{ $categoryPercentages['pr'] }}%
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full bg-orange-700 mr-2"></div>
                        <div class="flex-1 text-sm text-neutral-600 dark:text-neutral-300">Selesai</div>
                        <div class="font-medium text-neutral-800 dark:text-white">{{ $categoryPercentages['pr'] }}%
                        </div>
                    </div>
                </div>
            </div>
            @endhasanyrole

            <!-- Response Time Metrics -->
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 flex flex-col">
                <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-200 mb-3">Response Time</h3>
                <div class="flex items-baseline mb-1">
                    <span class="text-3xl font-bold text-neutral-800 dark:text-white">4.2</span>
                    <span class="text-sm ml-1 text-neutral-500 dark:text-neutral-400">hrs avg.</span>
                </div>
                <div class="text-sm font-medium text-emerald-600 dark:text-emerald-500 flex items-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                    0.8h faster
                </div>
                {{-- <div class="mt-auto space-y-1">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-neutral-500 dark:text-neutral-400 w-16">High</span>
                        <div class="flex-1 mx-2">
                            <div class="bg-neutral-100 dark:bg-neutral-800 rounded-full h-1.5">
                                <div class="bg-rose-500 h-1.5 rounded-full" style="width: 30%"></div>
                            </div>
                        </div>
                        <span class="text-xs font-medium w-8 text-right">1.5h</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-neutral-500 dark:text-neutral-400 w-16">Medium</span>
                        <div class="flex-1 mx-2">
                            <div class="bg-neutral-100 dark:bg-neutral-800 rounded-full h-1.5">
                                <div class="bg-amber-500 h-1.5 rounded-full" style="width: 60%"></div>
                            </div>
                        </div>
                        <span class="text-xs font-medium w-8 text-right">3.2h</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-neutral-500 dark:text-neutral-400 w-16">Low</span>
                        <div class="flex-1 mx-2">
                            <div class="bg-neutral-100 dark:bg-neutral-800 rounded-full h-1.5">
                                <div class="bg-blue-500 h-1.5 rounded-full" style="width: 85%"></div>
                            </div>
                        </div>
                        <span class="text-xs font-medium w-8 text-right">7.8h</span>
                    </div>
                </div> --}}
            </div>
        </div>
        <!-- bar chart area -->
        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @endpush
        @hasanyrole('head_verifier')
        <div class=" rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 h-64" wire:ignore>
            <h2 class="text-center font-semibold text-2xl">📈 Permohonan Sistem Informasi, Data dan Kehumasan</h2>
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
                            datasets: [{
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
                                        title: function(context) {
                                            return context[0].label + ' {{ date("Y") }}';
                                        },
                                        label: function(context) {
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


                document.addEventListener('DOMContentLoaded', function() {
                    initMonthlyChart();
                });


                document.addEventListener('livewire:navigated', function() {
                    setTimeout(initMonthlyChart, 100);
                });
            </script>
            @endpush
        </div>
        @endhasanyrole
        @hasanyrole('si_verifier')
        <div class=" rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 max-h-72" wire:ignore>
            <h2 class="text-center font-semibold text-2xl">📈 Permohonan Sistem Informasi</h2>
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
                                label: 'Permohonan Sistem Informasi',
                                data: raw.letterData,
                                backgroundColor: letterGradient,
                                borderColor: '#1F2937',
                                borderWidth: 2,
                                borderRadius: 8,
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
            <h2 class="text-center font-semibold sm:text-2xl text-[12px]">📈 Chart Permohonan Data</h2>
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

                    const letterGradient = ctx.createLinearGradient(0, 0, 0, 400);
                    letterGradient.addColorStop(0, 'rgba(31, 41, 55, 0.8)'); // #1F2937 @ 80%
                    letterGradient.addColorStop(1, 'rgba(31, 41, 55, 0.2)'); // #1F2937 @ 20%

                    dataDivChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: dataDiv.months,
                            datasets: [{
                                label: 'Permohonan Data',
                                data: dataDiv.letterData,
                                backgroundColor: letterGradient,
                                borderColor: '#1F2937',
                                borderWidth: 2,
                                borderRadius: 8,
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
            <h2 class="text-center font-semibold sm:text-2xl text-[12px]">📈 Chart Permohonan Kehumasan</h2>
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

                    // Buat gradient warna orange (mirip head_verifier PR dataset)
                    const prGradient = ctx.createLinearGradient(0, 0, 0, 400);
                    prGradient.addColorStop(0, 'rgba(249, 115, 22, 0.8)'); // orange-600 @80%
                    prGradient.addColorStop(1, 'rgba(249, 115, 22, 0.2)'); // orange-600 @20%

                    prChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: raw.months,
                            datasets: [{
                                label: 'Humas',
                                data: raw.prData,
                                backgroundColor: prGradient,
                                borderColor: '#F97316', // Tailwind orange-600
                                borderWidth: 2,
                                borderRadius: 8,
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