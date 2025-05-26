<div class="w-full px-4 pb-12">
    {{-- Desktop horizontal --}}
    <div class="hidden md:block">
        <div class="relative flex items-center justify-between w-full">
            <div class="absolute left-0 top-2/4 h-0.5 w-full -translate-y-2/4 bg-gray-300 dark:bg-gray-600"></div>
            <div class="absolute left-0 top-2/4 h-0.5 -translate-y-2/4 transition-all duration-500
                {{ $isRejected && $currentIndex === 3 ? 'bg-red-500 dark:bg-red-600' : 'bg-zinc-600 dark:bg-zinc-500' }}"
                style="width: {{ $currentIndex == 0 ? '0%' : ($currentIndex / (count($statuses) - 1)) * 100 . '%' }}">
            </div>

            {{-- Status items --}}
            @foreach ($statuses as $index => $step)
                @if (is_array($step) && isset($step['label'], $step['icon']))
                    <div class="relative flex flex-col items-center">
                        {{-- Dot --}}
                        <div class="relative z-10 grid w-12 h-12 rounded-full place-items-center font-bold transition-all duration-300
                            @if ($index <= $currentIndex)
                                {{ $step['label'] === 'Rejected' ? 'bg-red-500 dark:bg-red-600' : 'bg-zinc-600 dark:bg-zinc-500 text-white' }}
                            @else
                                bg-gray-300 dark:bg-gray-500 text-gray-900 dark:text-gray-100
                            @endif">
                            <x-dynamic-component :component="'lucide-' . $step['icon']" class="w-6 text-white" />
                        </div>

                        {{-- Label --}}
                        <span class="absolute mt-16 text-xs font-medium text-center whitespace-wrap
                            @if ($step['label'] === 'Rejected') text-red-500 dark:text-red-600
                            @elseif ($index <= $currentIndex)
                                text-zinc-600 dark:text-zinc-500
                            @else
                                text-gray-500 dark:text-gray-400
                            @endif">
                            {{ $step['label'] }}
                        </span>
                    </div>
                @else
                    <div>Error: Invalid status data.</div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- Mobile vertical --}}
    <div class="block md:hidden">
        <div class="relative flex flex-col items-start pl-6">
            {{-- Vertical line --}}
            <div class="absolute left-[14px] top-0 h-full w-0.5 bg-gray-300 dark:bg-gray-600 z-0"></div>
            <div class="absolute left-[14px] top-0 w-0.5 transition-all duration-500 z-10
            {{ $isRejected && $currentIndex === 3 ? 'bg-red-500 dark:bg-red-600' : 'bg-zinc-600 dark:bg-zinc-500' }}"
                style="height: {{ $currentIndex == 0 ? '0%' : ($currentIndex / (count($statuses) - 1)) * 100 . '%' }}">
            </div>

            {{-- Status items --}}
            @foreach ($statuses as $index => $step)
                <div class="relative flex items-center mb-10 last:mb-0">
                    {{-- Dot --}}
                    <div class="relative z-20 w-4 h-4 rounded-full flex items-center justify-center font-bold
                    @if ($index <= $currentIndex) {{ $index == 3 && $isRejected ? 'bg-red-500 dark:bg-red-600 text-white' : 'bg-zinc-600 dark:bg-zinc-500 text-white' }}
                    @else
                    bg-gray-300 dark:bg-gray-500 text-gray-900 dark:text-gray-100 @endif">
                        <x-dynamic-component :component="'lucide-' . $step['icon']" class="w-6 text-white" />
                    </div>

                    {{-- Label --}}
                    <div class="ml-4 text-sm font-medium leading-tight
                    @if ($index == 3 && $isRejected) text-red-500 dark:text-red-600
                    @elseif ($index <= $currentIndex)
                        text-zinc-600 dark:text-zinc-500
                    @else
                    text-gray-500 dark:text-gray-400 @endif">
                        {{ $step['label'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>