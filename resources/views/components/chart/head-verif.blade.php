<canvas id="totalChart" class="mt-4 mb-4"></canvas>
<script>
    function initTotalChart() {
        // Data dari DashboardController
        const data = @json($monthlyLetterData ?? ['months' => [], 'informationSystem' => [], 'publicRelation' => []]);

        // Cek apakah element ada
        const chartElement = document.getElementById('totalChart');
        if (!chartElement) return;

        // Destroy chart yang sudah ada
        if (chartElement.chart instanceof Chart) {
            chartElement.chart.destroy();
        }

        // Dapatkan context
        const ctx = chartElement.getContext('2d');

        chartElement.chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.months,
                datasets: [{
                    label: 'Sistem Informasi',
                    data: data.informationSystem,
                },
                {
                    label: 'Data',
                    data: data.data,
                },
                {
                    label: 'Humas',
                    data: data.publicRelation,
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
                            },
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
                            },
                            stepSize: 1,
                            callback: function (value) {
                                return Math.floor(value) === value ? value : '';
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
        initTotalChart();
    });

    document.addEventListener('livewire:navigated', function () {
        setTimeout(initTotalChart, 100);
    });
</script>