<div class="inline-flex">
    <section class="space-y-2">
        <p class="text-xs font-medium text-gray-500 uppercase">Detail Meeting</p>
        <div class="space-y-2">
            <div class="flex items-center text-sm">
                @if (isset($meeting['location']))
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 mr-2"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    <span class="font-medium text-gray-700">Lokasi:</span>
                    <span class="ml-2 text-gray-600">{{ $meeting['location'] }}</span>
                @elseif (isset($meeting['link']))
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 mr-2"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="23 7 16 12 23 17 23 7"></polygon>
                        <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                    </svg>
                    <span class="font-medium text-gray-700">
                        Online meeting:
                        <a href="{{ $meeting['link'] }}" target="_blank" rel="noopener noreferrer"
                            class="text-blue-600 hover:text-blue-800 underline">
                            Link
                        </a>
                    </span>
                @endif
            </div>
            <div class="flex items-center text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 mr-2"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12,6 12,12 16,14"></polyline>
                </svg>
                <span class="font-medium text-gray-700">Jadwal:</span>
                <span class="ml-2 text-gray-600">{{ $meeting['date'] }} • {{ $meeting['start'] }} -
                    {{ $meeting['end']}}</span>
            </div>
        </div>
    </section>
</div>