<div>
    <flux:button :href="route('history')" icon="arrow-long-left" variant="subtle">Kembali</flux:button>
    <div class="bg-white border-b border-gray-200 px-4 py-6">
        <div class="max-w-screen-xl mx-auto">
            <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
                <h2 class="text-2xl font-semibold text-gray-900">Detail Permohonan</h2>
            </div>
        </div>
    </div>

    <div class="py-6">
        <x-user.card-basic-info :id="$letter->id" :created-at="$letter->createdAtDMY()" :status="$letter->status"
            :title="$letter->title" :person="$letter->responsible_person" :activerevision="$letter->active_revision" :uploadedFile="$this->uploadedFile" />
    </div>

    <x-user.tracking-progres :status="$letter->status" />

    

</div>