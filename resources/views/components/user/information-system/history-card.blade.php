<!-- card-history.blade.php -->
<div class="w-full max-w-4xl md:ml-5 2xl:ml-5 mb-3">
    <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-sm transition-shadow cursor-pointer">
        <!-- Header -->
        <div class="flex items-center justify-between mb-3">
            <div>
                <h2 class="font-medium text-gray-900">{{ $letter->title }}</h2>
                <p class="text-xs text-gray-500">{{ $letter->reference_number }}</p>
            </div>
            <div class="flex items-center space-x-2 text-xs">
                <flux:notification.status-badge :status="$letter->status" />
                <span class="text-gray-500"> {{ $letter->created_at }}</span>
            </div>
        </div>

        <!-- Progress -->
        <div class="mb-3">
            <div class="flex items-center text-sm mb-1">
                <span class="text-gray-600">Progress</span>
                <span class="font-medium text-blue-800 ml-30">{{ $letter->status->percentage() }}</span>
            </div>
            <div class="w-50 bg-gray-200 rounded-full h-1">
                <div class="bg-blue-800 h-1 rounded-full" style="width: {{ $letter->status->percentage() }}"></div>
            </div>
        </div>

        <!-- Meeting -->
        @if ($letter->meeting)
            <div class="bg-gray-50 rounded p-3 mb-3">
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center">
                        @if (isset($letter->meeting['location']))
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 mr-2" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <span class="text-gray-700">Meeting di {{ $letter->meeting['location'] }}</span>
                        @elseif (isset($letter->meeting['link']))
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 mr-2" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                            </svg>
                            <span class="text-gray-700">Online meeting: <a href="{{ $letter->meeting['link'] }}" target="_blank"
                                    rel="noopener noreferrer" class="text-blue-600 hover:text-blue-800 underline">
                                    Link
                                </a></span>
                        @endif
                    </div>
                    <span class="text-gray-600">{{ $letter->getFormattedMeetingDate()}} • {{ $letter->meeting['start'] }} -
                        {{ $letter->meeting['end'] }}</span>
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="flex items-center justify-end gap-2">
            <button href="{{ route('history.detail', ['type' => 'information-system', $letter->id]) }}"
                class="bg-white hover:bg-zinc-100 text-black border-1 px-4 py-1.5 rounded text-sm font-medium cursor-pointer"
                wire:navigate>
                Details
            </button>

            @if ($letter->active_revision)
                <button href="{{ route('letter.edit', [$letter->id]) }}"
                    class="bg-orange-100 hover:bg-zinc-100 text-orange-700 border-1 px-4 py-1.5 rounded text-sm font-medium"
                    wire:navigate>
                    Revisi
                </button>
            @endif
        </div>
    </div>

</div>