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