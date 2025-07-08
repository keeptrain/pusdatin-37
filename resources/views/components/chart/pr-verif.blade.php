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