@props([
    'step',
    'index',
    'currentIndex',
    'isRejected',
    'type' => 'desktop' // desktop or mobile
])

@php
    $isActive = $index <= $currentIndex;
    $isRejectedStep = ($type === 'desktop' && $step['label'] === 'Ditolak') || 
                      ($type === 'mobile' && $index == 2 && $isRejected);
    
    // Warna berdasarkan status
    $dotBgClass = $isActive
        ? ($isRejectedStep ? 'bg-red-400 dark:bg-red-600' : 'bg-zinc-600 dark:bg-zinc-500 text-white')
        : 'bg-gray-300 dark:bg-gray-500 text-gray-900 dark:text-gray-100';
    
    $textColorClass = $isRejectedStep
        ? 'text-red-500 dark:text-red-600'
        : ($isActive ? 'text-zinc-600 dark:text-zinc-500' : 'text-gray-500 dark:text-gray-400');

    $tooltipContent = isset($step['created_at']) && $step['created_at']
        ? Carbon\Carbon::parse($step['created_at'])->format('d M Y H:i')
        : '';    
@endphp

@if (is_array($step) && isset($step['label'], $step['icon']))
    @if ($type === 'desktop')

        {{-- Desktop version --}}
        @if ($tooltipContent)
            <flux:tooltip content="{{ $tooltipContent }}">
                <div class="relative flex flex-col items-center">
                    <div class="relative z-10 grid w-12 h-12 rounded-full place-items-center font-bold transition-all duration-300 {{ $dotBgClass }}">
                        <x-dynamic-component :component="'lucide-' . $step['icon']" class="w-6 text-white" />
                    </div>
                    <span class="absolute mt-16 text-xs font-medium text-center whitespace-wrap {{ $textColorClass }}">
                        {{ $step['label'] }}
                    </span>
                </div>
            </flux:tooltip>
        @else
            <div class="relative flex flex-col items-center">
                <div class="relative z-10 grid w-12 h-12 rounded-full place-items-center font-bold transition-all duration-300 {{ $dotBgClass }}">
                    <x-dynamic-component :component="'lucide-' . $step['icon']" class="w-6 text-white" />
                </div>
                <span class="absolute mt-16 text-xs font-medium text-center whitespace-wrap {{ $textColorClass }}">
                    {{ $step['label'] }}
                </span>
            </div>
        @endif
    @else
        {{-- Mobile version --}}
        @if ($tooltipContent)
        <flux:tooltip content="{{ $tooltipContent }}">
            <div class="relative flex items-center mb-10 last:mb-0">
                <div class="relative z-20 p-4 rounded-full flex items-center justify-center font-bold {{ $dotBgClass }}">
                    <x-dynamic-component :component="'lucide-' . $step['icon']" class="w-5 h-5 text-white" />
                </div>
                <div class="ml-4 text-sm font-medium leading-tight {{ $textColorClass }}">
                    {{ $step['label'] }}
                </div>
            </div>
        </flux:tooltip>
        @else
            <div class="relative flex items-center mb-10 last:mb-0">
                <div class="relative z-20 p-4 rounded-full flex items-center justify-center font-bold {{ $dotBgClass }}">
                    <x-dynamic-component :component="'lucide-' . $step['icon']" class="w-5 h-5 text-white" />
                </div>
                <div class="ml-4 text-sm font-medium leading-tight {{ $textColorClass }}">
                    {{ $step['label'] }}
                </div>
            </div>
        @endif
    @endif
@else
    <div>Error: Invalid status data.</div>
@endif