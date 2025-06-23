<div class="max-w-screen-xl lg:px-0 mx-auto">
    <div class="w-fulll p-6 bg-white rounded-lg border">
        <h2 class="text-lg font-bold text-gray-800 mb-8">Tracking Progress</h2>

        <x-tracking.status-stepped :status="$status" :currentIndex="$this->currentIndex" :isRejected="$this->isRejected"
            :statuses="$statuses" />

        <x-user.tracking-list :activity="$this->activities" />
    </div>
</div>