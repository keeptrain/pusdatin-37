<div>
    <flux:button :href="route('letter.table')" icon="arrow-long-left" variant="subtle">Back to Table</flux:button>

    <x-letters.detail-layout overViewRoute="pr.show" activityRoute="pr.activity" :id="$prRequestId">
        <div class="p-12">
            <flux:notification.status-stepped :status="$status" />
            <x-user.tracking-list :activity="$this->groupedActivities" />
        </div>
    </x-letters.detail-layout>
</div>