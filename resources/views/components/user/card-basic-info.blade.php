<div class="max-w-screen-xl mx-auto px-4 lg:px-0 mb-6">
    <div class="bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden">
        <!-- Header: Request ID & Status -->
        <div class="px-6 py-4 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">{{$title}}</h3>
                <p class="mt-1 text-sm text-gray-500">Submitted on {{$createdAt}}</p>
            </div>

            <div class="mt-4 md:mt-0">
                <span class="inline-flex items-center px-3 py-1 rounded-full {{ $status->badgeBg() }} text-white text-sm font-medium">
                    {{$status->label()}}
                </span>
            </div>

        </div>
        <hr class="border-gray-100">

        <!-- Body: Basic Info -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Nama Penanggung Jawab</p>
                    <p class="mt-1 text-gray-900">{{$person}}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Jenis Layanan</p>
                    <p class="mt-1 text-gray-900">Sistem Informasi & Data</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Estimasi Waktu Selesai</p>
                    <p class="mt-1 text-gray-900">-</p>
                </div>
            </div>
        </div>
    </div>
</div>