<!-- card-history.blade.php -->
<div class="w-full max-w-4xl md:ml-5 2xl:ml-5 ">
    <div class="bg-white rounded-xl  p-6 mb-4 border-2 border-gray-100">
        <div class="flex justify-between items-center mb-3 ">
            <div class="flex items-center w-1/2 ">
                <h2 class="text-xl font-bold text-gray-800">Tema: {{ $prRequest->theme }} </h2>
            </div>
            <div class="flex items-center {{ $prRequest->status->badgeBg() }} text-white px-3 py-1 rounded-full ">
                <x-dynamic-component :component=" 'lucide-' . $prRequest->status->icon() " class="w-4 h-4 mr-1" />
                <span class="text-sm font-medium">{{ $prRequest->status->label() }}</span>
            </div>
        </div>

        <div class="flex items-center gap-1 text-gray-500 mb-6">
            <x-lucide-clock class="w-[18px]" />
            <span class="text-sm">Diajukan {{ $prRequest->created_at }}</span>
        </div>

        <div class="flex justify-between flex-wrap flex-col sm:flex-row mb-8 gap-5">
            <div>
                <p class="text-gray-500 text-sm mb-1">Sasaran</p>
                <p class="font-semibold text-gray-800">{{ ucfirst($prRequest->target) }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm mb-1">Link Produksi </p>
                @if ($prRequest->links)
                    @foreach ($prRequest->links as $key => $value)
                        @php
                            $label = match ($key) {
                                1 => 'Audio',
                                2 => 'Infografis',
                                3 => 'Poster',
                                4 => 'Video'
                            }
                        @endphp
                        <li>
                            <a href="{{ $value }}" class="font-semibold text-blue-800">{{ $label }}</a>
                        </li>
                    @endforeach
                @else
                    <span>-</span>
                @endif
            </div>
            <div>
                <p class="text-gray-500 text-sm mb-1">Link Publikasi</p>
                <p class="font-semibold text-gray-800">-</p>
            </div>
        </div>

        <p class="text-gray-500 text-sm">Progress Layanan</p>
        <div class="flex flex-col items-baseline-last sm:items-center justify-between md:flex-row ">
            <div class="flex gap-1 items-center  w-[70%] ">
                <div class="w-[80%] sm:w-[30%] bg-gray-200 rounded-full h-2 progres-bar ">
                    <div class="bg-[#364872] h-2 rounded-full {{ $prRequest->status->percentageBar() }}"></div>
                </div>
                <span class="text-gray-600 font-medium">{{ $prRequest->status->percentage() }}</span>
            </div>

            <a href="{{ route('history.detail', ['type' => 'public-relation', $prRequest->id]) }}"
                class="ml-0bg-zinc-50 hover:bg-zinc-100 text-black border font-medium py-2 px-4 rounded-lg flex items-center transition duration-300 w-fit self-end">
                Detail
            </a>
        </div>
    </div>
</div>