<div x-data="{ activeTab: '{{ $activeTab }}' }">
  
  <x-user.header-history />

  <div class="py-6">
      <div x-show="activeTab === 'tab1'">
        @forelse ($this->letters as $letter)
        <x-user.information-system.history-card :id="$letter->id" :serviceRequest="$letter->status" :title="$letter->title"
          :status="$letter->status" :referenceNumber="$letter->reference_number" :created-at="$letter->created_at" :activeRevision="$letter->active_revision" />
        @empty
          <p class="text-center text-gray-500">No requests found.</p>
        @endforelse
        <div class="mt-8">
          {{ $this->letters->links() }}
        </div>
      </div>
      <div x-show="activeTab === 'tab2'">
        @forelse ($this->publicRelationRequests as $prRequest)
        <x-user.public-relation.history-card :prRequest="$prRequest"/>
        @empty
        <p class="text-center text-gray-500">No requests found.</p>
        @endempty
        <div class="mt-8">
          {{ $this->publicRelationRequests->links() }}
        </div>
      </div>
  </div>
</div>