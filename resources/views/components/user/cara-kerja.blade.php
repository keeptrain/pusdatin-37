<section id="cara-kerja" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-16 text-center">
            <h2 class="text-3xl font-bold text-center mb-5 relative 
     after:content-[''] after:absolute after:bottom-[-0.5rem] after:left-1/2 
     after:w-16 after:h-1 after:bg-[#364872] after:rounded-full 
     after:transform after:-translate-x-1/2">Cara Kerja</h2>
            <p class="mt-4 text-lg text-gray-600 max-w-3xl mx-auto">
                Proses pengajuan permintaan layanan yang sederhana dan efisien untuk memenuhi kebutuhan Anda.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {{-- Step 1 --}}
            <div class="flex flex-col items-center text-center">
                <div class="relative mb-8">
                    <div class="w-20 h-20 rounded-full bg-purple-100 flex items-center justify-center mb-4">
                        {{-- Ganti dengan ikon SVG atau ikon yang sesuai di Blade --}}
                        <x-lucide-clipboard-check class="text-purple-700 lucide lucide-clipboard-check w-12" />
                    </div>
                    <div class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-[#364872] text-white flex items-center justify-center font-bold">
                        1
                    </div>
                </div>
                <h3 class="text-xl font-semibold mb-3">Buat Permintaan</h3>
                <p class="text-gray-600">Buat permintaan layanan melalui portal dengan mengisi formulir yang tersedia.</p>
            </div>

            {{-- Step 2 --}}
            <div class="flex flex-col items-center text-center">
                <div class="relative mb-8">
                    <div class="w-20 h-20 rounded-full bg-teal-100 flex items-center justify-center mb-4">
                        <x-lucide-send class="text-teal-700 w-12" />
                    </div>
                    <div class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-[#364872] text-white flex items-center justify-center font-bold">
                        2
                    </div>
                </div>
                <h3 class="text-xl font-semibold mb-3">Kirim & Verifikasi</h3>
                <p class="text-gray-600">Kirim permintaan dan tunggu verifikasi dari tim kami untuk memastikan kejelasan permintaan.</p>
            </div>

            {{-- Step 3 --}}
            <div class="flex flex-col items-center text-center">
                <div class="relative mb-8">
                    <div class="w-20 h-20 rounded-full bg-orange-100 flex items-center justify-center mb-4">
                        <x-lucide-search class="text-orange-700 w-12" />
                    </div>
                    <div class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-[#364872] text-white flex items-center justify-center font-bold">
                        3
                    </div>
                </div>
                <h3 class="text-xl font-semibold mb-3">Proses Pengerjaan</h3>
                <p class="text-gray-600">Tim kami akan memproses permintaan Anda sesuai dengan prioritas dan tenggat waktu.</p>
            </div>

            {{-- Step 4 --}}
            <div class="flex flex-col items-center text-center">
                <div class="relative mb-8">
                    <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center mb-4">
                        <i class="text-blue-700 lucide lucide-clock" style="font-size: 36px;"></i>
                        <x-lucide-clock class="text-blue-700 w-12" />
                    </div>
                    <div class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-[#364872] text-white flex items-center justify-center font-bold">
                        4
                    </div>
                </div>
                <h3 class="text-xl font-semibold mb-3">Hasil & Evaluasi</h3>
                <p class="text-gray-600">Terima hasil layanan dan berikan evaluasi untuk meningkatkan kualitas layanan kami.</p>
            </div>
        </div>
    </div>
</section>