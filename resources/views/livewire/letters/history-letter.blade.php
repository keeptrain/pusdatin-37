<div>
  <div>
    <!-- Search Bar -->
    <x-user.header-history />

    <div class="py-6">
      @forelse ($this->letter as $letter)
      <x-user.card-history 
        :id="$letter->id"
        :title="$letter->title"
        :status="$letter->status"
        :referenceNumber="$letter->reference_number"
        :created-at="$letter->created_at"
      />
      @empty
      <p class="text-center text-gray-500">No requests found.</p>
      @endforelse

      <!-- Pagination -->
      <div class="mt-8">
        {{ $this->letter->links() }}
      </div>

    </div>

  </div>