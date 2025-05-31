<x-layouts.app :title="__('Templates')">
    <div class="lg:p-3">
        <!-- Header Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Template Dokumen</h1>
                    <p class="mt-2 text-gray-600">Buat atau perbarui template dokumen yang akan di gunakan oleh pemohon
                    </p>
                </div>
                <a href="{{ route('create.template') }}"
                    class="bg-testing-100 text-white px-3 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Template baru</span>
                </a>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-4 py-4" x-data="templateManager()">

            <!-- Search and Filter Bar -->
            <div class="bg-white rounded-xl shadow-sm p-3 mb-8">
                <div class="flex flex-col lg:flex-row gap-4">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" placeholder="Cari template dokumen..." x-model="searchQuery"
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="lg:w-48">
                        <select x-model="selectedCategory"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Kategori</option>
                            <option value="invoice">Invoice</option>
                            <option value="proposal">Proposal</option>
                            <option value="contract">Kontrak</option>
                            <option value="report">Laporan</option>
                        </select>
                    </div>

                    <!-- Format Filter -->
                    <div class="lg:w-32">
                        <select x-model="selectedFormat"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Format</option>
                            <option value="PDF">PDF</option>
                            <option value="DOC">DOC</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            {{-- <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Template</p>
                            <p class="text-2xl font-bold text-gray-900" x-text="templates.length"></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Unduhan</p>
                            <p class="text-2xl font-bold text-gray-900">1,944</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Populer Bulan Ini</p>
                            <p class="text-2xl font-bold text-gray-900">324</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-orange-100">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Diperbarui</p>
                            <p class="text-2xl font-bold text-gray-900">Hari Ini</p>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Template Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($templates as $template)
                    <div class="bg-white rounded-xl shadow-sm card-hover overflow-hidden">
                        <!-- Template Preview -->
                        <div class="template-preview h-48 p-6 flex items-center justify-center relative">
                            <div class="absolute top-4 right-4">
                                <span x-text="template.format"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    :class="template . format === 'PDF' ? 'bg-red-100 text-red-800' : template . format === 'DOC' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'"></span>
                            </div>
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>

                            <!-- Active Badge -->
                            <div class="absolute top-4 left-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $template->is_active ? 'bg-green-400' : 'bg-gray-400' }}"></span>
                                    {{ $template->is_active ? 'Aktif' : 'Tidak aktif' }}
                                </span>
                            </div>
                        </div>

                        <!-- Template Info -->
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-3">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $template->name }}</h3>
                            </div>

                            <!-- Template Stats -->
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <p class="text-md ">{{ $template->part_number_label }}</p>
                                <div class="flex items-center space-x-2">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10">
                                            </path>
                                        </svg>
                                        <span x-text="template.downloads + ' unduhan'"></span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>{{ $template->updated_at }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-3">
                                <flux:button class="w-full">Edit</flux:button>
                                <form action="{{ route('download.template', ['typeNumber' => $template->part_number]) }}"
                                    method="POST">
                                    @csrf
                                    <flux:button icon="arrow-down-tray" type="submit" />
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty State -->
            <div x-show="filteredTemplates.length === 0" class="text-center py-16">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <h3 class="text-xl font-medium text-gray-900 mb-2">Tidak ada template yang ditemukan</h3>
                <p class="text-gray-500 mb-6">Coba ubah filter pencarian atau kata kunci Anda</p>
                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    Reset Filter
                </button>
            </div>
        </div>

        {{--
        <script>
            function templateManager() {
                return {
                    searchQuery: '',
                    selectedCategory: '',
                    selectedFormat: '',
                    templates: [
                        {
                            id: 1,
                            name: 'SPBE',
                            description: 'Standard invoice template with company branding and itemized billing sections.',
                            format: 'PDF',
                            category: 'invoice',
                            downloads: 324,
                            updated: '2 hari lalu'
                        },
                        {
                            id: 2,
                            name: 'SOP',
                            description: 'Standard operating procedure template for business processes.',
                            format: 'DOC',
                            category: 'report',
                            downloads: 156,
                            updated: '1 minggu lalu'
                        },
                        {
                            id: 3,
                            name: 'Pemanfaatan Aplikasi',
                            description: 'Application utilization template with detailed usage metrics.',
                            format: 'XLS',
                            category: 'report',
                            downloads: 89,
                            updated: '3 hari lalu'
                        },
                        {
                            id: 4,
                            name: 'RFC',
                            description: 'Request for Change template for IT infrastructure modifications.',
                            format: 'PDF',
                            category: 'proposal',
                            downloads: 245,
                            updated: '5 hari lalu'
                        },
                        {
                            id: 5,
                            name: 'NDA',
                            description: 'Non-disclosure agreement template for confidential business matters.',
                            format: 'DOC',
                            category: 'contract',
                            downloads: 178,
                            updated: '1 hari lalu'
                        },
                        {
                            id: 6,
                            name: 'Audio',
                            description: 'Audio processing and documentation template for media projects.',
                            format: 'PDF',
                            category: 'report',
                            downloads: 67,
                            updated: '4 hari lalu'
                        }
                    ],

                    get filteredTemplates() {
                        return this.templates.filter(template => {
                            const matchesSearch = template.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                template.description.toLowerCase().includes(this.searchQuery.toLowerCase());
                            const matchesCategory = !this.selectedCategory || template.category === this.selectedCategory;
                            const matchesFormat = !this.selectedFormat || template.format === this.selectedFormat;

                            return matchesSearch && matchesCategory && matchesFormat;
                        });
                    },

                    downloadTemplate(template) {
                        // Simulate download
                        alert(`Mengunduh template: ${template.name}`);
                        // In real application, trigger actual download
                        template.downloads++;
                    }
                }
            }
        </script> --}}

    </div>
</x-layouts.app>