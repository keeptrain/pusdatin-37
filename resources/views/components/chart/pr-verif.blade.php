<canvas id="pr-chart" class="mt-4 mb-4"></canvas>
<script>
    function initPrChart() {
        const raw = @json($monthlyLetterData);
        const canvas = document.getElementById('pr-chart');
        if (!canvas) return;

        // Destroy existing chart if it exists
        if (canvas.chart instanceof Chart) {
            canvas.chart.destroy();
        }

        const ctx = canvas.getContext('2d');
        canvas.chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: raw.months,
                datasets: [{
                    data: raw.publicRelation,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
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
                            label: ctx => ctx.parsed.y
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

    document.addEventListener('DOMContentLoaded', initPrChart);
    document.addEventListener('livewire:navigated', () => setTimeout(initPrChart, 100));
</script>