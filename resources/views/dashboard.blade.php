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
        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
            <!-- Chart Header -->
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="text-lg font-medium text-neutral-700 dark:text-neutral-200">Service Request Timeline</h3>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Last 30 days activity</p>
                </div>
                <div class="flex space-x-2">
                    <button
                        class="px-3 py-1 rounded-md bg-neutral-100 dark:bg-neutral-800 text-sm font-medium">Week</button>
                    <button
                        class="px-3 py-1 rounded-md bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 text-sm font-medium">Month</button>
                    <button
                        class="px-3 py-1 rounded-md bg-neutral-100 dark:bg-neutral-800 text-sm font-medium">Quarter</button>
                </div>
            </div>

            <!-- Chart Area -->
            <div class="h-64 relative">
                <!-- Chart SVG -->
                <svg class="w-full h-full" viewBox="0 0 800 240" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Grid Lines -->
                    <line x1="0" y1="0" x2="800" y2="0" stroke="#E5E7EB" stroke-dasharray="4 4" />
                    <line x1="0" y1="60" x2="800" y2="60" stroke="#E5E7EB" stroke-dasharray="4 4" />
                    <line x1="0" y1="120" x2="800" y2="120" stroke="#E5E7EB" stroke-dasharray="4 4" />
                    <line x1="0" y1="180" x2="800" y2="180" stroke="#E5E7EB" stroke-dasharray="4 4" />
                    <line x1="0" y1="240" x2="800" y2="240" stroke="#E5E7EB" stroke-dasharray="4 4" />

                    <!-- Completed Requests Area -->
                    <path
                        d="M0,240 L0,180 L27,170 L54,160 L81,165 L108,155 L135,140 L162,130 L189,120 L216,100 L243,105 L270,95 L297,85 L324,90 L351,80 L378,75 L405,85 L432,80 L459,75 L486,65 L513,60 L540,55 L567,65 L594,60 L621,50 L648,55 L675,45 L702,50 L729,40 L756,35 L783,30 L800,25 L800,240 Z"
                        fill="url(#blue-gradient)" fill-opacity="0.5" />
                    <path
                        d="M0,180 L27,170 L54,160 L81,165 L108,155 L135,140 L162,130 L189,120 L216,100 L243,105 L270,95 L297,85 L324,90 L351,80 L378,75 L405,85 L432,80 L459,75 L486,65 L513,60 L540,55 L567,65 L594,60 L621,50 L648,55 L675,45 L702,50 L729,40 L756,35 L783,30 L800,25"
                        stroke="#3B82F6" stroke-width="2" fill="none" />

                    <!-- Pending Requests Line -->
                    <path
                        d="M0,200 L27,195 L54,190 L81,195 L108,185 L135,190 L162,180 L189,185 L216,180 L243,175 L270,170 L297,165 L324,160 L351,155 L378,150 L405,155 L432,150 L459,145 L486,140 L513,135 L540,130 L567,125 L594,120 L621,115 L648,110 L675,105 L702,100 L729,95 L756,90 L783,85 L800,80"
                        stroke="#F59E0B" stroke-width="2" fill="none" />

                    <!-- Data Points for Completed -->
                    <circle cx="0" cy="180" r="4" fill="#3B82F6" />
                    <circle cx="108" cy="155" r="4" fill="#3B82F6" />
                    <circle cx="216" cy="100" r="4" fill="#3B82F6" />
                    <circle cx="324" cy="90" r="4" fill="#3B82F6" />
                    <circle cx="432" cy="80" r="4" fill="#3B82F6" />
                    <circle cx="540" cy="55" r="4" fill="#3B82F6" />
                    <circle cx="648" cy="55" r="4" fill="#3B82F6" />
                    <circle cx="756" cy="35" r="4" fill="#3B82F6" />

                    <!-- Data Points for Pending -->
                    <circle cx="0" cy="200" r="4" fill="#F59E0B" />
                    <circle cx="108" cy="185" r="4" fill="#F59E0B" />
                    <circle cx="216" cy="180" r="4" fill="#F59E0B" />
                    <circle cx="324" cy="160" r="4" fill="#F59E0B" />
                    <circle cx="432" cy="150" r="4" fill="#F59E0B" />
                    <circle cx="540" cy="130" r="4" fill="#F59E0B" />
                    <circle cx="648" cy="110" r="4" fill="#F59E0B" />
                    <circle cx="756" cy="90" r="4" fill="#F59E0B" />

                    <!-- Gradients -->
                    <defs>
                        <linearGradient id="blue-gradient" x1="400" y1="0" x2="400" y2="240"
                            gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#3B82F6" stop-opacity="0.2" />
                            <stop offset="100%" stop-color="#3B82F6" stop-opacity="0" />
                        </linearGradient>
                    </defs>
                </svg>

                <!-- Y-axis Labels -->
                <div
                    class="absolute top-0 left-0 h-full flex flex-col justify-between text-xs text-neutral-500 dark:text-neutral-400 py-1">
                    <div>50</div>
                    <div>40</div>
                    <div>30</div>
                    <div>20</div>
                    <div>10</div>
                    <div>0</div>
                </div>
            </div>

            <!-- X-axis Labels -->
            <div class="flex justify-between text-xs text-neutral-500 dark:text-neutral-400 px-6 mt-2">
                <div>May 1</div>
                <div>May 5</div>
                <div>May 10</div>
                <div>May 15</div>
                <div>May 20</div>
                <div>May 25</div>
                <div>May 30</div>
            </div>

            <!-- Legend -->
            <div class="flex items-center justify-center space-x-6 mt-4">
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-blue-500 mr-2"></div>
                    <span class="text-sm text-neutral-600 dark:text-neutral-300">Completed Requests</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-amber-500 mr-2"></div>
                    <span class="text-sm text-neutral-600 dark:text-neutral-300">Pending Requests</span>
                </div>
            </div>
        </div>
        <div class=" rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
            <canvas id="lettersBarChart"></canvas>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('lettersBarChart').getContext('2d');

                    const labels = @json($labels);
                    const data = @json($data);


                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Total Letters per Bulan',
                                data: [0, 0, 0, 0, 5, 0, 2],
                                backgroundColor: '#1F2937' // Tailwind bg-gray-800
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Bulan'
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Jumlah Letter'
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                title: {
                                    display: true,
                                    text: `Total Letters (${new Date().getFullYear()})`
                                }
                            }
                        }
                    });
                });
            </script>
        </div>


    </div>

</x-layouts.app>