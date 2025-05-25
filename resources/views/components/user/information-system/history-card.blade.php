<!-- card-history.blade.php -->
<div class="w-full max-w-4xl md:ml-5 2xl:ml-5 ">
    <div class="bg-white rounded-xl  p-6 mb-4 border-2 border-gray-100">
        <div class="flex justify-between items-center mb-3 ">
            <div class="flex items-center md:w-1/2 w-[70%] ">
                <h2 class="text-xl font-bold text-gray-800">{{$title}}</h2>
            </div>
            <div class="flex items-center {{ $status->badgeBg() }} text-white px-3 py-1 rounded-full ">
                <x-dynamic-component :component=" 'lucide-' . $status->icon() " class="w-4 mr-1" />
                <span class="md:text-sm text-[10px] font-medium">{{ $status->label() }}</span>
            </div>
        </div>

        <div class="flex items-center gap-1 text-gray-500 mb-6">
            <x-lucide-clock class="w-[18px]" />
            <span class="text-sm">Diajukan {{$createdAt}}</span>
        </div>

        <div class="flex justify-between flex-wrap flex-col sm:flex-row mb-8 gap-5">
            <div>
                <p class="text-gray-500 text-sm mb-1">Nomor Surat</p>
                <p class="font-semibold text-gray-800">{{$referenceNumber}}</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm mb-1">Jenis Layanan</p>
                <p class="font-semibold text-gray-800">Sistem Informasi & data</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm mb-1">Estimasi Waktu Selesai</p>
                <p class="font-semibold text-gray-800">-</p>
            </div>
        </div>

        <p class="text-gray-500 text-sm">Progress Layanan</p>
        <div class="flex flex-col items-baseline-last sm:items-center justify-between md:flex-row">
            <div class="flex gap-1 items-center  w-[70%] ">
                <div class="w-[80%] sm:w-[30%] bg-gray-200 rounded-full h-2 progres-bar ">
                    <div class="bg-[#364872] h-2 rounded-full {{ $status->percentageBar() }}"></div>
                </div>
                <span class="text-gray-600 font-medium">{{ $status->percentage() }}</span>
            </div>
            <div class="flex gap-3 self-end md:mt-0 mt-5">
                <template x-if="{{ $activeRevision }}">
                    <a href="{{ route('letter.edit', [$id]) }}"
                        class="ml-0 sm:ml-4 bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-4 rounded-lg flex items-center transition duration-300 w-fit self-end">
                        Revisi
                        <x-lucide-edit class="text-white w-5 ml-2" />
                    </a>
                </template>
                <a href="{{ route('history.detail', ['type' => 'information-system' ,$id]) }}"
                    class="ml-0bg-zinc-50 hover:bg-zinc-100 text-black border font-medium py-2 px-4 rounded-lg flex items-center transition duration-300 w-fit self-end">
                    Detail
                </a>
            </div>

        </div>
    </div>
</div>