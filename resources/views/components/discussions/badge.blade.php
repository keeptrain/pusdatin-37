@props([
    'status'  => null,
    'label'   => null,
    'id'      => null,
])

@php
switch($status) {
    case App\Models\PublicRelationRequest::class:
        $badgeClass = 'bg-amber-100 text-amber-800 hover:bg-amber-200 dark:bg-amber-900 dark:text-amber-100 dark:hover:bg-amber-800';
        $routeName = 'pr.show';
        $type = 'public-relation';
        break;
    case App\Models\InformationSystemRequest::class:
        $badgeClass = 'bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900 dark:text-green-100 dark:hover:bg-green-800';
        $routeName = 'is.show';
        $type = 'information-system';
        break;
    default:
        $badgeClass = 'bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-gray-900 dark:text-gray-100 dark:hover:bg-gray-800';
        $routeName = null;
        $type = null;
}

$isUser = auth()->user()->currentUserRoleId() === 7;
$route = $routeName ? ($isUser
          ? route('detail.request', ['type' => $type, 'id' => $id])
          : route($routeName, $id))
        : null;
@endphp

<span @class([
        'inline-flex items-center gap-1 px-2.5 py-1 rounded-sm text-xs font-medium transition-colors',
        $badgeClass,
        'cursor-pointer' => $route,
     ])
     @if($route) onclick="Livewire.navigate('{{ $route }}')" @endif>
    {{ $label }}
</span>