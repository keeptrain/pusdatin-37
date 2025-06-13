<section class="py-20 ">
    <div class="max-w-6xl mx-auto px-4 text-center">

        {{-- Judul --}}
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Layanan <span class="text-[#435d94]">Sistem Informasi & Data</span>
        </h2>

        {{-- Deskripsi --}}
        <p class="text-gray-600 max-w-2xl mx-auto mb-12 text-sm md:text-base leading-relaxed">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi iaculis blandit erat et
            vulputate. Fusce et lacinia tortor. Aliquam erat volutpat. Nullam aliquet mollis neque eu luctus.
        </p>


        <div class="grid grid-cols-1 gap-6">

            {{-- Upload Data --}}
            <a href="{{route('letter.upload')}}"
                class="border border-blue-500 rounded-xl p-10 flex flex-col items-center hover:bg-blue-50 transition">
                <x-lucide-cloud-upload class="w-16" />
                <h3 class="text-xl font-semibold text-gray-800">Upload Data</h3>
                <p class="text-sm text-gray-500 mt-1">Click to upload your files</p>
            </a>

        </div>

    </div>
</section>