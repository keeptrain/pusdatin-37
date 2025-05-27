<div class="space-y-2">
    <div class="flex items-center">
        @if (isset($meeting['location']))
            <flux:icon.map-pin class="size-5 mr-2" />
            <p class="text-gray-800">{{ $meeting['location'] }}</p>

        @elseif (isset($meeting['link']))
            <flux:icon.video-camera class="size-5 mr-2" />
            <a href="{{ $meeting['link'] }}" target="_blank" rel="noopener noreferrer"
                class="text-blue-800 hover:text-blue-800 underline">
                Link
            </a>
        @endif
    </div>
    <div class="flex items-start">
        <flux:icon.clock class="size-5 mr-2" />
        <div class="flex flex-col">
            <p class="text-gray-600">{{ $date }}</p>
            <p class="text-gray-600">{{ $meeting['start'] }} - {{ $meeting['end']}}</p>
        </div>
    </div>
</div>