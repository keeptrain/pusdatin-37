<div>
<div>
  <!-- Search Bar -->
  <div class="bg-white border-b border-gray-200 px-4 py-6">
    <div class="max-w-screen-xl mx-auto flex justify-end">
      <flux:input
    wire:model.live.debounce.500ms="searchQuery" 
    placeholder="Search requests..."
    class="w-full sm:w-64"/>
    </div>
  </div>

    <div class=" py-6">
        @forelse($tracks as $track)
        <x-user.card-history
            :requestId="$track->id"
            :title="$track->letter->title"
            :status="$track->letter->status"
            :reference-number="$track->letter->reference_number"
            created-at="{{ $track->created_at->format('M d, Y') }}" />
        @empty
        <p class="text-center text-gray-500">No requests found.</p>
        @endforelse

        <!-- Pagination -->
    <div class="mt-8">
      {{ $tracks->links() }}
    </div>

    </div>

</div>