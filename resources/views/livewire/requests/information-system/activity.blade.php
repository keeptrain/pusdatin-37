<div>
    <flux:button :href="route('is.index')" icon="arrow-long-left" variant="subtle">Kembali ke Tabel</flux:button>

    <flux:heading size="lg" class="p-4">Aktivitas Permohonan Layanan</flux:heading>
    
    <x-layouts.requests.show overViewRoute="is.show" activityRoute="is.activity" :id="$siRequestId">
        <div class="p-12">
            <flux:notification.status-stepped :status="$status" :isRejected="$this->isRejected"
                :currentIndex="$this->currentIndex" :statuses="$this->statuses" />

            <x-user.tracking-list :activity="$this->groupedActivities" />
        </div>
    </x-layouts.requests.show>
</div>