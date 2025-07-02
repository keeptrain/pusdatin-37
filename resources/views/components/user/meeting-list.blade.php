<section class="py-16 px-4 mt-10">
    <div class="max-w-6xl mx-auto">
        {{-- Header Section --}}
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-[#364872] mb-4">Jadwal Meeting</h2>
            <p class="text-gray-600 text-lg">Informasi Jadwal Meeting Anda</p>
        </div>

        {{-- Meeting Card --}}
        <div class="bg-white shadow-lg rounded-lg border-l-4 border-l-[#364872]">
            {{-- Card Header --}}
            <div class="bg-[#4b639c] bg-opacity-10 px-6 py-4 rounded-t-lg">
                <h3 class="text-xl text-[#ffffff] font-semibold flex items-center">
                    <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Jadwal Meeting
                </h3>
            </div>

            {{-- Card Content --}}
            <div class="p-6">
                <div class="space-y-6">

                    {{-- Meeting 1 --}}
                    <div class="grid md:grid-cols-2 gap-6">
                        {{-- Left Column --}}
                        <div class="space-y-4">
                            {{-- Date --}}
                            <div class="flex items-center space-x-3">
                                <svg class="h-5 w-5 text-[#364872]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Tanggal</p>
                                    <p class="font-semibold text-gray-800">Senin, Juli 01</p>
                                </div>
                            </div>

                            {{-- Time --}}
                            <div class="flex items-center space-x-3">
                                <svg class="h-5 w-5 text-[#364872]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Waktu</p>
                                    <p class="font-semibold text-gray-800">09:00 - 10:30</p>
                                </div>
                            </div>

                            {{-- Location/Link --}}
                            <div class="flex items-center space-x-3">
                                <svg class="h-5 w-5 text-[#364872]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Lokasi</p>
                                    <p class="font-semibold text-gray-800">
                                        <a href="https://meet.google.com/abc-defg-hij"
                                            class="text-[#364872] hover:underline"
                                            target="_blank"
                                            rel="noopener noreferrer">
                                            Google Meet - meet.google.com/abc-defg-hij
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Right Column --}}
                        <div class="space-y-4">
                            {{-- Meeting Password --}}
                            <div class="flex items-center space-x-3">
                                <svg class="h-5 w-5 text-[#364872]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Kode Meeting</p>
                                    <p class="font-semibold text-gray-800 font-mono bg-gray-100 px-2 py-1 rounded">MTG123456</p>
                                </div>
                            </div>

                            {{-- Meeting Topic --}}
                            <div class="flex items-start space-x-3">
                                <svg class="h-5 w-5 text-[#364872] mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Topik</p>
                                    <p class="font-semibold text-gray-800">Rapat Evaluasi Sistem Informasi</p>
                                </div>
                            </div>

                            {{-- Meeting Status --}}
                            <div class="flex items-start space-x-3">
                                <svg class="h-5 w-5 text-green-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Status</p>
                                    <p class="font-semibold text-green-600">Terjadwal</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Separator --}}
                    <div class="border-t border-gray-200 pt-6"></div>

                    {{-- Meeting 2 --}}
                    <div class="grid md:grid-cols-2 gap-6">
                        {{-- Left Column --}}
                        <div class="space-y-4">
                            {{-- Date --}}
                            <div class="flex items-center space-x-3">
                                <svg class="h-5 w-5 text-[#364872]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Tanggal</p>
                                    <p class="font-semibold text-gray-800">Selasa, Juli 02</p>
                                </div>
                            </div>

                            {{-- Time --}}
                            <div class="flex items-center space-x-3">
                                <svg class="h-5 w-5 text-[#364872]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Waktu</p>
                                    <p class="font-semibold text-gray-800">14:00 - 15:30</p>
                                </div>
                            </div>

                            {{-- Location/Link --}}
                            <div class="flex items-center space-x-3">
                                <svg class="h-5 w-5 text-[#364872]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Lokasi</p>
                                    <p class="font-semibold text-gray-800">Ruang Rapat Lt. 3 - UPT Pusdatin</p>
                                </div>
                            </div>
                        </div>

                        {{-- Right Column --}}
                        <div class="space-y-4">
                            {{-- Meeting Topic --}}
                            <div class="flex items-start space-x-3">
                                <svg class="h-5 w-5 text-[#364872] mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Topik</p>
                                    <p class="font-semibold text-gray-800">Koordinasi Layanan Kehumasan</p>
                                </div>
                            </div>

                            {{-- Meeting Status --}}
                            <div class="flex items-start space-x-3">
                                <svg class="h-5 w-5 text-green-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Status</p>
                                    <p class="font-semibold text-green-600">Terjadwal</p>
                                </div>
                            </div>

                            {{-- Peserta --}}
                            <div class="flex items-start space-x-3">
                                <svg class="h-5 w-5 text-[#364872] mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Peserta</p>
                                    <p class="font-semibold text-gray-800">Tim Kehumasan & Kepala UPT</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Separator --}}
                    <div class="border-t border-gray-200 pt-6"></div>

                    {{-- Meeting 3 --}}
                    <div class="grid md:grid-cols-2 gap-6">
                        {{-- Left Column --}}
                        <div class="space-y-4">
                            {{-- Date --}}
                            <div class="flex items-center space-x-3">
                                <svg class="h-5 w-5 text-[#364872]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Tanggal</p>
                                    <p class="font-semibold text-gray-800">Rabu, Juli 03</p>
                                </div>
                            </div>

                            {{-- Time --}}
                            <div class="flex items-center space-x-3">
                                <svg class="h-5 w-5 text-[#364872]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Waktu</p>
                                    <p class="font-semibold text-gray-800">10:00 - 12:00</p>
                                </div>
                            </div>

                            {{-- Location/Link --}}
                            <div class="flex items-center space-x-3">
                                <svg class="h-5 w-5 text-[#364872]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Lokasi</p>
                                    <p class="font-semibold text-gray-800">
                                        <a href="https://zoom.us/j/123456789"
                                            class="text-[#364872] hover:underline"
                                            target="_blank"
                                            rel="noopener noreferrer">
                                            Zoom Meeting - zoom.us/j/123456789
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Right Column --}}
                        <div class="space-y-4">
                            {{-- Meeting Password --}}
                            <div class="flex items-center space-x-3">
                                <svg class="h-5 w-5 text-[#364872]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Kode Meeting</p>
                                    <p class="font-semibold text-gray-800 font-mono bg-gray-100 px-2 py-1 rounded">ZOOM2025</p>
                                </div>
                            </div>

                            {{-- Meeting Topic --}}
                            <div class="flex items-start space-x-3">
                                <svg class="h-5 w-5 text-[#364872] mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Topik</p>
                                    <p class="font-semibold text-gray-800">Pelatihan Penggunaan Aplikasi Jakreq</p>
                                </div>
                            </div>

                            {{-- Meeting Status --}}
                            <div class="flex items-start space-x-3">
                                <svg class="h-5 w-5 text-orange-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Status</p>
                                    <p class="font-semibold text-orange-500">Menunggu Konfirmasi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak Ada Meeting Hari Ini</h3>
                    <p class="mt-1 text-sm text-gray-500">Kamu Tidak Punya Jadwal Meeting Hari Ini</p>
                </div>

            </div>
        </div>
    </div>
</section>