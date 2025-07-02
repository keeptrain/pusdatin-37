<div class="mb-18">
    {{-- Desktop horizontal --}}
    <div class="hidden md:block">
        <div class="relative flex items-center justify-between w-full">
            <div class="absolute left-0 top-2/4 h-0.5 w-full -translate-y-2/4 bg-gray-300 dark:bg-gray-600"></div>
            <div class="absolute left-0 top-2/4 h-0.5 -translate-y-2/4
                {{ $isRejected && $currentIndex === 2 ? 'bg-red-400 dark:bg-red-600' : 'bg-zinc-600 dark:bg-zinc-500' }}"
                style="width: {{ $currentIndex == 0 ? '0%' : ($currentIndex / (count($statuses) - 1)) * 100 . '%' }}">
            </div>

            {{-- Status items --}}
            @foreach ($statuses as $index => $step)
                <x-tracking.partials.stepped-adapter :step="$step" :index="$index" :current-index="$currentIndex"
                    :is-rejected="$isRejected" type="desktop" />
            @endforeach
        </div>
    </div>

    {{-- Mobile vertical --}}
    <div class="block md:hidden">
        <div class="relative flex flex-col items-start pl-6">
            <div class="absolute left-[50px] top-0 h-full w-0.5 bg-gray-300 dark:bg-gray-600 z-0"></div>
            <div class="absolute left-[50px] top-0 w-0.5
            {{ $isRejected && $currentIndex === 2 ? 'bg-red-500 dark:bg-red-600' : 'bg-zinc-600 dark:bg-zinc-500' }}"
                style="height: {{ $currentIndex == 0 ? '0%' : ($currentIndex / (count($statuses) - 1)) * 100 . '%' }}">
            </div>

            {{-- Status items --}}
            @foreach ($statuses as $index => $step)
                <x-tracking.partials.stepped-adapter :step="$step" :index="$index" :current-index="$currentIndex"
                    :is-rejected="$isRejected" type="mobile" />
            @endforeach
        </div>
    </div>
</div>