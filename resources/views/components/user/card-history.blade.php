<!-- card-history.blade.php -->
<div class="w-full max-w-4xl md:ml-5 2xl:ml-10 ">
    <div class="bg-white rounded-xl shadow-lg p-6 mb-4 border border-gray-100">
        <div class="flex justify-between items-center mb-6 ">
            <div class="flex items-center w-1/2 ">
                <h2 class="text-xl font-bold text-gray-800">{{$title}}</h2>
            </div>
            <div class="flex items-center bg-gray-200 text-gray-700 px-3 py-1 rounded-full">
                <x-lucide-hourglass class="w-[16px]" />
                <span class="text-sm font-medium">In Review</span>
            </div>
        </div>

        <div class="flex items-center text-gray-500 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-2">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <span class="text-sm">Submited on May 2, 2025</span>
        </div>

        <div class="flex justify-between flex-wrap flex-col sm:flex-row mb-8 gap-5">
            <div>
                <p class="text-gray-500 text-sm mb-1">Request ID</p>
                <p class="font-semibold text-gray-800">REQ-AP-2542</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm mb-1">Jenis Layanan</p>
                <p class="font-semibold text-gray-800">Layanan Sistem Informasi & Data</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm mb-1">Estimasi Waktu Selesai</p>
                <p class="font-semibold text-gray-800">May 15, 2025</p>
            </div>
        </div>

        <div class="flex flex-col items-baseline-last  sm:items-center justify-between md:flex-row ">
            <div class="flex gap-1 items-center  w-[60%] ">
                <div class="w-[80%] sm:w-[30%] bg-gray-200 rounded-full h-2 progres-bar    ">
                    <div class="bg-[#364872] h-2 rounded-full" style="width: 50%"></div>
                </div>
                <span class="text-gray-600 font-medium">50%</span>
            </div>
            <a href="{{ route('history.detail', ['track' => $requestId]) }}" class="ml-0 sm:ml-4 bg-[#364872] hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-lg flex items-center transition duration-300 w-fit self-end">
                View Detail
                <x-lucide-arrow-right class="text-white w-5" />
            </a>
        </div>
    </div>
</div>