@props(['logs'])

@php
    // Group logs by letter_id and sort by date
    $groupedLogs = $logs->groupBy('letter_id')->map(function ($logs) {
        return $logs->sortBy('created_at');
    });
@endphp

<div class="space-y-8">
    @foreach($groupedLogs as $letterId => $letterLogs)
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold mb-4">Letter #{{ $letterId }} Timeline</h3>
            
            <div class="relative overflow-x-auto pb-4">
                <div class="flex flex-nowrap space-x-8">
                    @foreach($letterLogs as $log)
                        <div class="flex-shrink-0 relative">
                            <div class="flex flex-col items-center">
                                {{-- Timeline Dot --}}
                                <div class="w-4 h-4 rounded-full bg-blue-500 mb-2 relative z-10
                                    @switch($log->action)
                                        @case('created') bg-green-500 @break
                                        @case('updated') bg-yellow-500 @break
                                        @case('deleted') bg-red-500 @break
                                        @default bg-blue-500
                                    @endswitch">
                                </div>
                                
                                {{-- Date --}}
                                <span class="text-sm text-gray-600 mb-1">
                                    {{ $log->created_at->format('d M Y') }}
                                </span>
                                
                                {{-- Action --}}
                                <div class="px-3 py-2 bg-gray-50 rounded-lg text-sm font-medium
                                    @switch($log->action)
                                        @case('created') text-green-700 bg-green-50 @break
                                        @case('updated') text-yellow-700 bg-yellow-50 @break
                                        @case('deleted') text-red-700 bg-red-50 @break
                                        @default text-gray-700
                                    @endswitch">
                                    {{ ucfirst($log->action) }}
                                </div>
                            </div>

                            {{-- Connector Line --}}
                            @if(!$loop->last)
                                <div class="absolute top-[18px] left-full -ml-4 w-8 h-0.5 bg-gray-200"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>