<div class="p-4">
    <!-- Kolom 1: Lokasi/Link + Waktu -->
    <div class=" space-y-2">
        @if (isset($meeting['location']))
            <div class="flex items-center">
                <flux:icon.map-pin class="size-4 mr-2" />
                <div class="text-gray-900 ">
                    <p><span class="text-gray-500">Lokasi:</span> {{ $meeting['location'] }}</p>
                </div>
            </div>
        @elseif (isset($meeting['link']))
            <div class="flex items-center ">
                <flux:icon.video-camera class="size-4 mr-2" />
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
            <flux:icon.calendar class="size-4 mr-2" />
            <div class="flex text-gray-900">
                <p>{{ $meeting['date'] }} • </p>
                <p class="ml-1">{{ $meeting['start'] }} - {{ $meeting['end'] }}</p>
            </div>
        </div>
        <flux:modal.trigger name="history-meeting">
            <flux:button variant="subtle" size="xs" class=" mt-2">
                Lihat lebih lengkap
            </flux:button>
        </flux:modal.trigger>

        {{-- Modal yang berisi meeting lainnya --}}
        <flux:modal name="history-meeting">
            <flux:legend>Riwayat Meeting</flux:legend>
            <div class="space-y-2 mt-4">
                @foreach ($this->meeting as $value)
                    <x-user.information-system.meeting-history :meeting="$value" />
                @endforeach
            </div>
        </flux:modal>
    </div>
</div>