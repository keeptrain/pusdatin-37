 <div class=" rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 h-64" wire:ignore>
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