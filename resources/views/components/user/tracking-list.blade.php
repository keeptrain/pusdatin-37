@foreach ($activity as $date => $hours)
    <!-- Activity history -->
    <div class="border border-gray-200 rounded-lg mt-4">
        <div class="p-4 flex items-center">
            <flux:icon.calendar />
            <flux:heading class="ml-3">{{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
            </flux:heading>
        </div>

        @foreach ($hours as $hour => $trackingHistorie)
            <div class="border-t border-gray-200">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center p-4">
                    <div class="mb-3 md:mb-0">
                        @foreach ($trackingHistorie as $historie)
                            <flux:subheading class="max-w-[800px] overflow-wrap-break">
                                {{ $historie->message }}
                            </flux:subheading>
                            @if (!empty($historie->notes))
                                Catatan: {{ $historie->notes }}
                            @endif
                        @endforeach
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center md:space-x-4">
                        <div class="mb-2 md:mb-0">
                            {{ $hour }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endforeach