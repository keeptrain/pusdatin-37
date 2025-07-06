@props([
    'status' => null,  // The model class (e.g., App\Models\InformationSystemRequest)
    'label' => null,   // Display text for the badge
    'id' => null,      // ID for route parameter
])

@php
    use App\Models\PublicRelationRequest;
    use App\Models\InformationSystemRequest;

    // Determine badge colors
    $statusBadge = match ($status) {
        PublicRelationRequest::class => 'bg-amber-100 text-amber-800 hover:bg-amber-200',
        InformationSystemRequest::class => 'bg-green-100 text-green-800 hover:bg-green-200',
        default => 'bg-gray-100 text-gray-800 hover:bg-gray-200',
    };

    // Determine route names
    $routeName = match ($status) {
        PublicRelationRequest::class => 'pr.show',
        InformationSystemRequest::class => 'is.show',
        default => null,
    };

    // Only make clickable if route exists
    $isClickable = !is_null($routeName);
@endphp
   <span @class([
    'inline-flex items-center gap-1 px-2.5 py-1 rounded-sm text-xs font-medium transition-colors',
    $statusBadge,
    'cursor-pointer' => $isClickable,
]) 
@if($isClickable)
    onclick="window.location.href='{{ route($routeName, $id) }}'"
@endif
>
    {{ $label }}
</span>