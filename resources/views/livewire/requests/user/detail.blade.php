<section>
    <flux:button :href="route('list.request')" icon="arrow-long-left" variant="subtle">Kembali</flux:button>
    <div class="bg-white border-b border-gray-200 px-4 py-3 mb-3">
        <div class="bg-white border-b border-gray-200 px-4 py-3 mb-3">
            <div class="max-w-screen-xl mx-auto">
                <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
                    <h2 class="text-2xl font-semibold text-gray-900">Detail Permohonan</h2>
                </div>
            </div>
        </div>

        @if ($content->status instanceof App\States\PublicRelation\Completed || $content->status instanceof App\States\InformationSystem\Completed)
            <div x-data="{ visible: true }" x-show="visible" x-collapse class="mb-3">
                <div x-show="visible" x-transition>
                    <flux:callout icon="star" color="sky">
                        <flux:callout.heading>Rating permohonan</flux:callout.heading>
                        <flux:callout.text>Untuk memberikan rating terhadap permohonan yang telah selesaikan, silahkan klik
                            tombol beri rating.
                        </flux:callout.text>

                        <x-slot name="actions">
                            <flux:button x-on:click="$dispatch('modal-show', { name: 'rating-modal' });">Beri rating
                            </flux:button>
                        </x-slot>

                        <x-slot name="controls">
                            <flux:button icon="x-mark" variant="ghost" x-on:click="visible = false" />
                        </x-slot>
                    </flux:callout>
                </div>
            </div>
            <x-modal.rating-for-user />
        @endif

        @if ($type === 'information-system')
            @if (!$this->needUploadAdditionalFile)
                <flux:callout icon="exclamation-triangle" color="yellow" class="mb-3">
                    <flux:callout.text>
                        Kamu belum memberikan dokumen pendukung berupa (Surat Perjanjian Kerasahasiaan).
                        <flux:callout.link x-on:click="$dispatch('modal-show', { name: 'upload-modal' });"
                            class="cursor-pointer">
                            Silahkan upload disini</flux:callout.link>
                    </flux:callout.text>
                </flux:callout>
                <x-modal.additional-file-upload />
            @endif
            <x-user.information-system.card-basic-info :id="$content->id" :created-at="$content->createdAtDMY()"
                :contact="$content->user->contact" :status="$content->status" :title="$content->title"
                :person="$content->user->name" :activerevision="$content->active_revision"
                :uploadedFile="$this->uploadedFile" :meeting="$content->meeting"
                :referenceNumber="$content->reference_number" />

            <x-user.information-system.card-progress-info :status="$content->status" :currentIndex="$this->currentIndex"
                :statuses="$this->statuses" :activity="$this->activities" :isRejected="$this->isRejected" />
            {{-- <x-user.tracking-progres :status="$content->status" /> --}}
        @elseif ($type === 'public-relation')
            <x-user.public-relation.card-basic-info :prRequest="$content" />

            <x-user.public-relation.card-progress-info :status="$content->status" :currentIndex="$this->currentIndex"
                :statuses="$this->statuses" :activity="$this->activities" :isRejected="false" />
        @endif
</section>