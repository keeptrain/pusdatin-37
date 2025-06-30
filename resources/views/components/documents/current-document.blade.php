<div x-data="{ selectedPart: '{{ $mapping->first()['part_number'] ?? null }}' }" class="p-4">
    <div class="mb-4">
        <flux:heading size="lg">{{ $title }}</flux:heading>
    </div>

    {{-- Tabs with hidden scrollbar --}}
    <div class="bg-white w-full sticky top-0 z-10">
        <nav class="flex gap-6 overflow-x-auto scrollbar-hide pb-1 items-center">
            <flux:legend>Bagian:</flux:legend>
            @foreach ($mapping as $map)
                <button
                    type="button"
                    class="py-2 text-sm font-medium transition-colors duration-200 shrink-0 border-b-2 cursor-pointer"
                    :class="{
                        'border-transparent text-gray-400 hover:text-gray-700 hover:border-gray-300': selectedPart !== '{{ $map['part_number'] }}',
                        'border-zinc-800 text-zinc-800': selectedPart === '{{ $map['part_number'] }}'
                    }"
                    x-on:click="selectedPart = '{{ $map['part_number'] }}'"
                >
                    {{ $map['part_number_label'] }}
                </button>
            @endforeach
        </nav>
    </div>

    {{-- PDF Viewer --}}
    <div class="h-[700px] bg-gray-100 overflow-hidden mt-2 rounded-xl">
        @foreach ($mapping as $map)
            <div x-show="selectedPart === '{{ $map['part_number'] }}'" class="w-full h-full">
                @if ($map['file_path'])
                    <iframe
                        src="{{ asset($map['file_path']) }}"
                        class="w-full h-full"
                        frameborder="0"
                        loading="lazy"
                    ></iframe>
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-500">
                        File tidak tersedia.
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

<style>
    /* Hide scrollbar but keep functionality */
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
</style>