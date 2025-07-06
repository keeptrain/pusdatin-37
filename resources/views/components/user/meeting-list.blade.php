<div class="space-y-4 p-2 mb-4">
    {{-- Header total rapat hari ini --}}
    <div class="mb-4 flex items-center space-x-4">
        {{-- <x-lucide-calendar class="size-7" /> --}}
        <flux:heading size="xl" class="flex items-center text-testing-100 text-center gap-2">
            @if ($todayMeetingCount > 0)
            <x-lucide-calendar-days class="size-7" /> Hari ini terdapat {{ $todayMeetingCount }} meeting
            @else
            <x-lucide-calendar class="size-7" /> Tidak terdapat meeting hari ini
            @endif
        </flux:heading>
    </div>

    {{-- Loop for 3 days ahead--}}
    @foreach ($meetingList as $dateGroup)
    {{-- Header tanggal --}}
    <div class="flex items-center gap-4">
        {{-- Tanggal --}}
        <flux:heading size="xl"
            class="text-5xl {{ $dateGroup['has_meetings'] ? 'text-testing-100' : 'text-gray-300' }}">
            {{ $dateGroup['date_number'] }}
        </flux:heading>

        {{-- Hari dan bulan --}}
        <div class="font-medium -space-y-1">
            <flux:subheading size="xl" class="{{ $dateGroup['has_meetings'] ? 'text-testing-100 ' : 'text-gray-300' }}">
                {{ $dateGroup['date_day'] }}
            </flux:subheading>
            <flux:subheading size="xl" class="{{ $dateGroup['has_meetings'] ? 'text-gray-400' : 'text-gray-300' }}">
                {{ $dateGroup['date_month'] }}
            </flux:subheading>
        </div>
    </div>

    {{-- Daftar rapat --}}
    @if($dateGroup['has_meetings'])
    @foreach($dateGroup['meetings'] as $meeting)
    <div wire:key="meeting-{{ $meeting['id'] }}" class="flex">
        <div class="hidden lg:block lg:w-[50px]"></div> <!-- Offset untuk border start & end -->
        <div class="border rounded-lg p-3 bg-zinc-50 flex items-center w-full">
            <!-- Icon -->
            @if (($meeting['place']['type']) === 'link')
            <flux:icon.video-camera class="size-12 mr-4 bg-blue-100 text-blue-900 rounded-full p-3" />

            @elseif(($meeting['place']['type']) === 'location')
            <flux:icon.map-pin class="size-12 mr-4 bg-amber-100 text-amber-900 rounded-full p-3" />
            @endif

            <!-- Konten Utama -->
            <div class="flex flex-col flex-grow space-y-1.5">
                <!-- Lokasi Meeting -->
                <flux:subheading size="lg" class="text-black text-sm">
                    @if ($meeting['place']['type'] === 'link')
                    <flux:link x-data="{
                                        link: '{{ $meeting['place']['value'] ?? '' }}',
                                        isTruncated: false }"
                        x-init="isTruncated = typeof link === 'string' && link.length > 50;"
                        :href="$meeting['place']['value'] ?? '#'">
                        <span x-text="isTruncated ? link.substring(0, 50) + '...' : link"></span>
                    </flux:link>
                    @if ($meeting['place']['password'])
                    <flux:text class="font-mono mt-2">Password: {{ $meeting['place']['password'] }}</flux:text>
                    @endif
                    @else
                    {{ $meeting['place']['value'] }}
                    @endif
                </flux:subheading>

                <!-- Topik di Tengah -->
                <div class="flex">
                    <flux:text>Topik: {{ $meeting['topic'] }}</flux:text>
                </div>
            </div>

            <!-- Jam di Akhir -->
            <div class="ml-auto flex items-center space-x-2">
                <flux:legend size="lg"><span class="text-gray-400">Jam: </span> {{ $meeting['time'] }}</flux:legend>
                @php
                $url = !auth()->user()->hasRole('user') ? route('is.show', ['id' => $meeting['request_id']]) : route('detail.request', ['type' => 'information-system', 'id' => $meeting['request_id']]);
                @endphp
                <flux:button icon="chevron-right" variant="subtle" wire:navigate href="{{ $url }}"></flux:button>
            </div>
        </div>
    </div>
    {{-- <flux:avatar.group class="mt-6">
                    <flux:avatar circle size="md" initials="AB" />
                    <flux:avatar circle size="md" initials="CD" />
                    <flux:avatar circle size="md" initials="EF" />
                    <flux:avatar circle size="md" initials="GH" />
                </flux:avatar.group> --}}
    @endforeach
    @endif
    @endforeach
</div>