<div>
  <div>
    <!-- Search Bar -->
    <x-user.header-history />

    <div class=" py-6">
      @forelse($tracks as $track)
      <x-user.card-history
        :requestId="$track->id"
        :title="$track->letter->title"
        :status="$track->letter->status"
        :reference-number="$track->letter->reference_number"
        :created-at="$track->created_at->format('M d, Y')" />
      @empty
      <p class="text-center text-gray-500">No requests found.</p>
      @endforelse

      <!-- Pagination -->
      <div class="mt-8">
        {{ $tracks->links() }}
      </div>

    </div>

  </div>