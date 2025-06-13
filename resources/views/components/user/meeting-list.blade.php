<div class="mt-6 space-y-4 p-4">
    {{-- Header total rapat hari ini --}}
    <div class="mb-4">
        <flux:heading size="xl" class="text-blue-900">
            @if ($todayMeetingCount > 0)
                Hari ini terdapat {{ $todayMeetingCount }} meeting
            @else
                Tidak terdapat meeting hari ini
            @endif
        </flux:heading>
    </div>

    {{-- Loop for 3 days ahead--}}
    @foreach ($meetingList as $dateGroup)
        {{-- Header tanggal --}}
        <div class="flex items-center gap-4">
            {{-- Tanggal --}}
            <flux:heading size="xl" class="text-5xl {{ $dateGroup['has_meetings'] ? 'text-blue-900' : 'text-gray-300' }}">
                {{ $dateGroup['date_number'] }}
            </flux:heading>

            {{-- Hari dan bulan --}}
            <div class="font-medium -space-y-1">
                <flux:subheading size="xl" class="{{ $dateGroup['has_meetings'] ? 'text-blue-900 ' : 'text-gray-300' }}">
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
                <div class="flex">
                    <div class="w-[65px]"></div> <!-- Offset untuk border start & end -->
                    <div class="border rounded-lg p-3 bg-zinc-50 flex items-center w-125">
                        @if (($meeting['link_location']['type']) === 'link')
                            <flux:icon.video-camera class="size-12 mr-4 bg-blue-100 text-blue-900 rounded-full p-3" />
                        @elseif(($meeting['link_location']['type']) === 'location')
                            <flux:icon.map-pin class="size-12 mr-4 bg-amber-100 text-amber-900 rounded-full p-3" />
                        @endif
                        <div class="space-y-0.5">
                            <flux:text>{{ $meeting['start'] }} - {{ $meeting['end'] }}</flux:text>
                            <flux:subheading size="lg" class="text-black text-sm">
                                @if ($meeting['link_location']['type'] === 'link')
                                        <flux:link x-data="{
                                                link: '{{ $meeting['link_location']['value'] }}',
                                                isTruncated: false
                                            }"
                                            x-init="
                                                isTruncated = link.length > 50;
                                            "
                                            :href="$meeting['link_location']['value']">
                                            <span x-text="isTruncated ? link.substring(0, 50) + '...' : link"></span>
                                        </flux:link>
                                    @if (!empty($meeting['link_location']['password']))
                                        <flux:text class="font-mono mt-2">Password: {{ $meeting['link_location']['password']}}</flux:text>
                                        {{-- <flux:input size="sm" variant="filled" :value="$meeting['link_location']['password']" readonly copyable /> --}}
                                    @endif
                                @else
                                    {{ $meeting['link_location']['value'] }}
                                @endif
                            </flux:subheading>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    @endforeach
</div>
