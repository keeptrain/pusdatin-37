<!-- No results for current filters -->
<div class="relative mb-6">
    <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
        <x-lucide-search-x class="size-10 text-gray-400" />
    </div>
    <div class="absolute -top-2 -right-2 w-6 h-6 bg-blue-400 rounded-full animate-bounce"></div>
    <div class="absolute -bottom-2 -left-2 w-4 h-4 bg-blue-600 rounded-full animate-bounce"
        style="animation-delay: 0.2s"></div>
</div>
<flux:heading size="lg">
    Diskusi tidak ditemukan
</flux:heading>
<p class="mt-2 text-gray-600">
    @if($search)
        Tidak ada hasil untuk pencarian "{{ $search }}"
    @else
        Tidak ada diskusi yang sesuai dengan filter yang dipilih
    @endif
</p>
<div class="mt-4">
    <flux:button x-on:click="$wire.resetFilters" variant="outline" size="sm">
        Reset Pencarian
    </flux:button>
</div>