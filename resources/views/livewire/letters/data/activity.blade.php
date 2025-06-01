<div>
    <flux:button :href="route('letter.table')" icon="arrow-long-left" variant="subtle">Back to Table</flux:button>

    <flux:heading size="xl" class="p-4">Aktivitas Permohonan Layanan</flux:heading>
    
    <x-letters.detail-layout overViewRoute="letter.detail" activityRoute="letter.activity" :id="$siRequestId">
        <div class="p-12">
            <flux:notification.status-stepped :status="$status" :isRejected="$this->isRejected"
                :currentIndex="$this->currentIndex" :statuses="$this->statuses" />

            <x-user.tracking-list :activity="$this->groupedActivities" />
        </div>
    </x-letters.detail-layout>
</div>