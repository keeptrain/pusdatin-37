<div x-data="{ selectedPart: '{{ $mapping->first()['part_number'] ?? null }}' }" class="p-4">
    <div class="items-center mb-4">
        <h2 class="text-md font-semibold text-gray-700">{{ $title }}</h2>
    </div>

    {{-- Selector --}}
    <div class="flex flex-wrap gap-2 mb-4">
        @foreach ($mapping as $map)
            @php $part = $map['part_number']; @endphp
            <flux:button x-on:click="selectedPart = '{{ $part }}'">
                {{ $map['part_number_label'] }} 
            </flux:button>
        @endforeach
    </div>

    {{-- PDF Viewer --}}
    <div class="h-[600px] bg-gray-100 rounded-lg overflow-hidden">
        @foreach ($mapping as $map)
            @php
                $file = $map['file_path'];
                $part = $map['part_number'];
            @endphp

            <div x-show="selectedPart === '{{ $part }}'" class="w-full h-full">
                @if ($file)
                    <iframe src="{{ asset($file) }}" class="w-full h-full" frameborder="0"></iframe>
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-500">
                        File tidak tersedia.
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>