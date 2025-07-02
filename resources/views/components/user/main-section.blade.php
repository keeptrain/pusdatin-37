{{-- resources/views/components/main-section.blade.php --}}
<div class="min-h-screen bg-white flex items-center justify-center px-4 py-16 relative">
    <div class="max-w-screen mx-auto text-center">

        <!-- Badge -->
        <div class="inline-flex items-center gap-2 bg-[#364872] bg-opacity-10 border border-[#364872] border-opacity-30 rounded-full px-4 py-2 mb-8">
            <div class="w-8 h-8 bg-[#364872] rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <span class=" font-medium  text-white text-lg">Jakreq</span>
        </div>

        <!-- Main Heading with Typing Animation -->
        <div class="mb-6 mx-auto flex flex-col justify-center items-center">
            <h1 class=" font-bold text-gray-900 leading-tight md:text-5xl uppercase mb-2 md:w-[79%]">
                Portal Layanan UPT PUSDATIN DINAS KESEHATAN
            </h1>
            <h2 class="block text-[#364872] font-semibold text-4xl 2xl:text-5xl mb-1">
                Ajukan Melalui Jakreq
            </h2>

        </div>

        <!-- Description -->
        <div class="mb-12 max-w-3xl mx-auto">
            <p class="text-[14px] md:text-xl text-gray-600 leading-relaxed">
                Portal ini digunakan untuk mengajukan layanan pembuatan aplikasi, permintaan data dan kehumasan. Permintaan akan ditangani sesuai dengan seksi yang anda pilih, pastikan teliti dan membaca saat mengajukan layanan <span class="font-extrabold capitalize">bacalah sop terlebih dahulu sebelum mengirim atau mengajukan layanan.</span>
            </p>
        </div>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('list.request') }}"
                wire:navigate
                class="group bg-[#364872] hover:bg-[#2d3a5f] text-white px-8 py-4 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-xl flex items-center gap-2 no-underline">
                <span>Ajukan Permohonan Sekarang</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>


    </div>
</div>