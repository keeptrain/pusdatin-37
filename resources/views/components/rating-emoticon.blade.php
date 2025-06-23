@props([
    'key' => null
])

<svg class="w-7 h-7" 
     :class="{
         'text-red-600': {{ $key }} === 1,
         'text-yellow-600': {{ $key }} === 2,
         'text-green-600': {{ $key }} === 3,
         'text-emerald-600': {{ $key }} === 4,
         'text-gray-600': ![1,2,3,4].includes({{ $key }})
     }" 
     fill="currentColor" 
     viewBox="0 0 24 24">
    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" fill="none" />
    
    <!-- Bad -->
    <g x-show="{{ $key }} === 1">
        <circle cx="8" cy="9" r="1.5" />
        <circle cx="16" cy="9" r="1.5" />
        <path d="M8 16s1.5-2 4-2 4 2 4 2" stroke="currentColor" stroke-width="1.5" fill="none" />
    </g>
    
    <!-- Okay -->
    <g x-show="{{ $key }} === 2">
        <circle cx="8" cy="9" r="1.5" />
        <circle cx="16" cy="9" r="1.5" />
        <line x1="8" y1="15" x2="16" y2="15" stroke="currentColor" stroke-width="1.5" />
    </g>
    
    <!-- Good -->
    <g x-show="{{ $key }} === 3">
        <circle cx="8" cy="9" r="1.5" />
        <circle cx="16" cy="9" r="1.5" />
        <path d="M8 14s1.5 2 4 2 4-2 4-2" stroke="currentColor" stroke-width="1.5" fill="none" />
    </g>
    
    <!-- Amazing -->
    <g x-show="{{ $key }} === 4">
        <circle cx="8" cy="9" r="1.5" />
        <circle cx="16" cy="9" r="1.5" />
        <path d="M7 13s1.5 3 5 3 5-3 5-3" stroke="currentColor" stroke-width="1.5" fill="none" />
    </g>
</svg>