<div x-data="{ selectedPart: '{{ $mapping->first()['part_number'] ?? null }}' }" class="p-4">
    <div class="items-center mb-4">
        <h2 class="text-md font-semibold text-gray-700">{{ $title }}</h2>
    </div>

    {{-- Selector --}}
    <div class="flex flex-wrap gap-2 mb-4">
        @foreach ($mapping as $map)
            <flux:button size="sm" x-on:click="selectedPart = '{{ $map['part_number'] }}'">
                {{ $map['part_number_label'] }}
            </flux:button>
        @endforeach
    </div>

    {{-- PDF Viewer --}}
    <div class="h-[700px] bg-gray-100 rounded-lg overflow-hidden">
        @foreach ($mapping as $map)
            <div x-show="selectedPart === '{{ $map['part_number'] }}'" class="w-full h-full">
                @if ($map['file_path'])
                    <iframe src="{{ asset($map['file_path']) }}" class="w-full h-full rounded-lg mt-2" frameborder="0"
                        loading="lazy"></iframe>
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-500">
                        File tidak tersedia.
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>