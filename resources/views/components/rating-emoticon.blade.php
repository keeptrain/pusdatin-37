@props([
    'key' => null,
    'showLabel' => true,
    'labelClass' => 'text-sm font-medium ml-2',
    'iconClass' => 'w-7 h-7'
])

@php
    $ratingData = [
        1 => ['label' => 'Terrible', 'color' => 'red-600'],
        2 => ['label' => 'Bad', 'color' => 'yellow-600'],
        3 => ['label' => 'Okay', 'color' => 'lime-600'],
        4 => ['label' => 'Good', 'color' => 'green-600'],
        5 => ['label' => 'Amazing', 'color' => 'emerald-600'],
    ];
    
    $currentRating = $ratingData[$key] ?? ['label' => 'Tidak Dinilai', 'color' => 'gray-600'];
    $colorClass = "text-{$currentRating['color']}";
@endphp

<div class="inline-flex items-center">
    <svg class="{{ $iconClass }} flex-shrink-0 {{ $colorClass }}" 
         fill="none" 
         viewBox="0 0 24 24"
         aria-label="Rating: {{ $currentRating['label'] }}">
         
        <!-- Base Circle -->
        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" />
        
        <!-- Eyes (Common for all ratings) -->
        <g x-show="{{ $key }} >= 2 && {{ $key }} <= 5">
            <circle cx="8" cy="9" r="1.5" fill="currentColor" />
            <circle cx="16" cy="9" r="1.5" fill="currentColor" />
        </g>
        
        <!-- Terrible (1) -->
        <g x-show="{{ $key }} === 1">
            <path d="M6.5 7.5l3 3M9.5 7.5l-3 3M14.5 7.5l3 3M17.5 7.5l-3 3" 
                  stroke="currentColor" 
                  stroke-width="1.5"
                  stroke-linecap="round" />
            <path d="M16 17s-1.5-3-4-3-4 3-4 3" 
                  stroke="currentColor" 
                  stroke-width="1.5"
                  stroke-linecap="round" />
        </g>
        
        <!-- Bad (2) -->
        <path x-show="{{ $key }} === 2" 
              d="M8 16s1.5-2 4-2 4 2 4 2" 
              stroke="currentColor" 
              stroke-width="1.5"
              stroke-linecap="round" />
        
        <!-- Okay (3) -->
        <line x-show="{{ $key }} === 3" 
              x1="8" y1="15" x2="16" y2="15" 
              stroke="currentColor" 
              stroke-width="1.5"
              stroke-linecap="round" />
        
        <!-- Good (4) -->
        <path x-show="{{ $key }} === 4" 
              d="M8 14s1.5 2 4 2 4-2 4-2" 
              stroke="currentColor" 
              stroke-width="1.5"
              stroke-linecap="round" />
        
        <!-- Amazing (5) -->
        <path x-show="{{ $key }} === 5" 
              d="M7 13s1.5 3 5 3 5-3 5-3" 
              stroke="currentColor" 
              stroke-width="1.5"
              stroke-linecap="round" />
    </svg>

    <span class="{{ $labelClass }} {{ $colorClass }}">
        {{ $currentRating['label'] }}
    </span>
</div>