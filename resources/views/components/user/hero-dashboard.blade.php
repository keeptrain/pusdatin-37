<section class="bg-[#f3f5f8] py-12 w-full">
    <div class="max-w-7xl mx-auto px-4 ">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- Card 1 --}}
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-blue-100 flex items-center justify-center">
                    <x-lucide-code class="w-6" />
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Permintaan Aplikasi</h3>
                <p class="mt-2 text-sm text-gray-600">Custom software solutions tailored to your needs</p>
                <a href="{{route('letter')}}" class=" mt-4 inline-block bg-[#364872] text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-[#2c3751] transition">
                    Ajukan Permohonan
                </a>
            </div>

            {{-- Card 2 --}}
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-green-100 flex items-center justify-center">
                    <x-lucide-database class="w-6" />
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Permintaan Data</h3>
                <p class="mt-2 text-sm text-gray-600">Secure and efficient data management services</p>
                <a href="#" class="mt-4 inline-block bg-[#364872] text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-[#2c3751] transition">
                    Ajukan Permohonan
                </a>
            </div>

            {{-- Card 3 --}}
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-purple-100 flex items-center justify-center">
                    <x-lucide-file-video class="w-6" />
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Humas Multimedia</h3>
                <p class="mt-2 text-sm text-gray-600">Comprehensive media and communication solutions</p>
                <a href="#" class="mt-4 inline-block bg-[#364872] text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-[#2c3751] transition">
                    Ajukan Permohonan
                </a>
            </div>

        </div>
    </div>
</section>