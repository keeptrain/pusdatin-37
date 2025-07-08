<div class="p-4">
    <!-- Kolom 1: Lokasi/Link + Waktu -->
    <div class=" space-y-2">
        @if ($nearestMeeting->place['type'] === 'location')
            <div class="flex items-center">
                <flux:icon.map-pin class="size-4 mr-2" />
                <div class="text-gray-900 ">
                    <p><span class="text-gray-500">Lokasi:</span> {{ $nearestMeeting->place['value'] }}</p>
                </div>
            </div>
        @elseif ($nearestMeeting->place['type'] === 'link')
            <div class="flex items-center ">
                <flux:icon.video-camera class="size-4 mr-2" />
                <div>
                    <span class="text-gray-500">Online:</span>
                    <a href="{{ $nearestMeeting->place['value'] }}" target="_blank" rel="noopener noreferrer"
                        class="text-blue-800 hover:text-blue-800 underline">
                        Link
                    </a>
                    @if (isset($nearestMeeting->place['password']))
                        <p class="text-gray-500">Password: {{ $nearestMeeting->place['password'] }}</p>
                    @endif
                </div>
            </div>
        @endif

        <div class="flex items-center">
            <flux:icon.calendar class="size-4 mr-2" />
            <div class="flex text-gray-900">
                <p>{{ $nearestMeeting->date }} • </p>
                <p class="ml-1">{{ $nearestMeeting->startAtTime }} - {{ $nearestMeeting->endAtTime }}</p>
            </div>
        </div>
        <flux:modal.trigger name="history-meeting">
            <flux:button variant="subtle" size="xs" class=" mt-2">
                Lihat lebih lengkap
            </flux:button>
        </flux:modal.trigger>

        {{-- Modal yang berisi meeting lainnya --}}
        <flux:modal name="history-meeting" class="w-1/2">
            <flux:legend>Riwayat Meeting</flux:legend>
            <div class="space-y-2 mt-4">
                @foreach ($meetings as $value)
                    <x-user.information-system.meeting-history :meeting="$value" />
                @endforeach
            </div>
        </flux:modal>
    </div>
</div>