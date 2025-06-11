<div>
    <flux:button :href="route('pr.index')" icon="arrow-long-left" variant="subtle">Back to Table</flux:button>

    <flux:heading size="xl" class="p-4">Aktivitas Permohonan Layanan</flux:heading>

    <x-layouts.requests.show overViewRoute="pr.show" activityRoute="pr.activity" :id="$prRequestId">
        <div class="p-12">
            <x-menu.public-relation.status-stepped :status="$this->status" :currentIndex="$this->currentIndex"  :statuses="$this->statuses"/>
            <x-user.tracking-list :activity="$this->groupedActivities" />
        </div>
    </x-layouts.requests.show>
</div>