<section>
    <flux:button :href="route('history')" icon="arrow-long-left" variant="subtle">Kembali</flux:button>
    <div class="bg-white border-b border-gray-200 px-4 py-6">
        <div class="max-w-screen-xl mx-auto">
            <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
                <h2 class="text-2xl font-semibold text-gray-900">Detail Permohonan</h2>
            </div>
        </div>
    </div>

    @if ($type === 'information-system')
        <div class="py-6">
            <x-user.information-system.card-basic-info :id="$content->id" :created-at="$content->createdAtDMY()"
                :status="$content->status" :title="$content->title" :person="$content->user->name"
                :activerevision="$content->active_revision" :uploadedFile="$this->uploadedFile" />
        </div>

        <x-user.tracking-progres :status="$content->status" />
    @elseif ($type === 'public-relation')
        <div class="py-6">
            <x-user.public-relation.card-basic-info :prRequest="$content" />
        </div>

        <x-user.tracking-progres :status="$content->status" />
    @endif

</section>