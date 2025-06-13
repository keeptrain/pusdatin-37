<div class="bg-zinc-50 rounded-lg border p-4">
    <!-- Kolom 1: Lokasi/Link + Waktu -->
    <div class="flex flex-col space-y-2">
        @if (isset($meeting['location']))
            <div class="flex items-center">
                <flux:icon.map-pin class="size-5 mr-2" />
                <div class="text-gray-900">
                    <p><span class="text-gray-500">Lokasi:</span> {{ $meeting['location'] }}</p>
                </div>
            </div>
        @elseif (isset($meeting['link']))
            <div class="flex items-center">
                <flux:icon.video-camera class="size-5 mr-2" />
                <div>
                    <span class="text-gray-500">Online:</span>
                    <a href="{{ $meeting['link'] }}" target="_blank" rel="noopener noreferrer"
                        class="text-blue-800 hover:text-blue-800 underline">
                        Link
                    </a>
                </div>
            </div>
        @endif

        <div class="flex items-center">
            <flux:icon.calendar class="size-5 mr-2" />
            <div class="flex text-gray-900">
                <p>{{ $meeting['date'] }} • </p>
                <p class="ml-1">{{ $meeting['start'] }} - {{ $meeting['end'] }}</p>
            </div>
        </div>
    </div>

    <!-- Kolom 2: Hasil Meeting -->
    <div class="flex flex-col mt-2" x-data="{ expanded: false }">
        <div class="flex items-start">
            <flux:icon.chat-bubble-left-right class="size-5 mr-2 mt-1" />
            <div class="flex-1">
                <p class="text-gray-500">Hasil meeting:</p>
                <p class="text-gray-900 transition-all duration-300 ease-in-out" :class="expanded ? '' : 'max-h-24 overflow-hidden line-clamp-4'"
                    x-text="expanded ? @js($meeting['result']) : @js(Str::limit($meeting['result'], 500))">
                </p>
                @if (strlen($meeting['result']) > 500)
                    <button x-on:click="expanded = !expanded"
                        class="self-start mt-1 text-blue-800 hover:underline text-sm focus:outline-none"
                        x-text="expanded ? 'Sembunyikan' : 'Lebih lengkap...'">
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>