<section x-data="{ openModal: false }" x-cloak>
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Card 1: Layanan SI & Data --}}
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-blue-100 flex items-center justify-center">
                    <x-lucide-code class="w-6" />
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Layanan Sistem Informasi & Data</h3>
                <p class="mt-2 text-sm text-gray-600">Custom software solutions tailored to your needs</p>
                <button
                    @click="openModal = true"
                    class="mt-4 inline-block bg-[#364872] text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-[#2c3751] transition">
                    Ajukan Permohonan
                </button>
            </div>

            {{-- Card 2: Layanan Kehumasan --}}
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-purple-100 flex items-center justify-center">
                    <x-lucide-file-video class="w-6" />
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Layanan Kehumasan</h3>
                <p class="mt-2 text-sm text-gray-600">Comprehensive media and communication solutions</p>
                <a href="{{ route('pr.form') }}"
                    class="mt-4 inline-block bg-[#364872] text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-[#2c3751] transition">
                    Ajukan Permohonan
                </a>
            </div>

        </div>
    </div>

    <!-- Modal Overlay -->
    <div
        x-show="openModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 "
        style="background-color: rgba(0,0,0,.5);"
        x-transition.opacity>
        <div
            class="bg-white rounded-xl shadow-2xl w-full max-w-md "
            x-transition
            x-show="openModal">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">
                    SOP Permohonan Sistem Informasi & Data
                </h2>
                <button @click="openModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6">
                <div class="grid grid-cols-1 gap-4">
                    <a
                        href="{{ asset('pdf/sop-sistem-informasi-data.pdf') }}"
                        download
                        @click.prevent="
              // buka PDF di tab baru (download)
              window.open($el.getAttribute('href'), '_blank');
              // kemudian redirect ke form setelah delay kecil
              setTimeout(() => { $wire.resetFilters && $wire.resetFilters(); /* jika pake Livewire */ window.location = '{{ route('si-data.form') }}'; }, 300);
            "
                        class="group block bg-red-50 border-2 border-red-100 rounded-xl p-6 text-center hover:border-red-200 hover:bg-red-100 transition-all duration-200">
                        <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-200 transition-colors duration-200">
                            <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                                <text x="7" y="17" font-family="Arial,sans-serif" font-size="3" font-weight="bold" fill="#dc2626">PDF</text>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Download PDF</h3>
                        <p class="text-sm text-gray-600">Unduh SOP dahulu, lalu lanjut form</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>