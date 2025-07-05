<div x-data="{ open: true }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="background-color: rgba(0,0,0,.5);">
    <div x-show="open" x-transition.opacity class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
        {{-- Header Modal --}}
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">
                Pilih Format
            </h2>
            <button id="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors duration-200"
                @click="open = false">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        {{-- Isi Modal --}}
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- pdf card -->
                <div class="group transition-all duration-200 hover:scale-105">
                    <div
                        class="bg-red-50 border-2 border-red-100 rounded-xl p-6 text-center hover:border-red-200 hover:bg-red-100 transition-all duration-200">
                        <!-- PDF Icon -->
                        <div
                            class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-200 transition-colors duration-200">
                            <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                                <text x="7" y="17" font-family="Arial, sans-serif" font-size="3" font-weight="bold"
                                    fill="#dc2626">
                                    PDF
                                </text>
                            </svg>
                        </div>

                        <h3 class="text-lg font-semibold text-gray-800 mb-2">
                            Sebagai PDF
                        </h3>

                        <a wire:click="exportAsPdf"
                            class="inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg group-hover:bg-red-700 transition-colors duration-200 cursor-none">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Download
                        </a>
                    </div>
                </div>

                <!-- Excel card -->
                <div class="group transition-all duration-200 hover:scale-105">
                    <div
                        class="bg-green-50 border-2 border-green-100 rounded-xl p-6 text-center hover:border-green-200 hover:bg-green-100 transition-all duration-200">
                        <!-- Excel Icon -->
                        <div
                            class="w-16 h-16 mx-auto mb-4 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors duration-200">
                            <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                                <text x="6.5" y="17" font-family="Arial, sans-serif" font-size="2.5" font-weight="bold"
                                    fill="#16a34a">
                                    XL
                                </text>
                            </svg>
                        </div>

                        <h3 class="text-lg font-semibold text-gray-800 mb-2">
                            Sebagai Excel
                        </h3>

                        <a wire:click="exportAsExcel" target="_blank"
                            class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg group-hover:bg-green-700 transition-colors duration-200 cursor-none">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Download
                        </a>

                    </div>
                </div>
            </div>
            <!-- info text -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-sm text-blue-800">
                        Hasil dokumen yang di download sudah berdasarkan data yang anda
                        input di formulir filter data
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>