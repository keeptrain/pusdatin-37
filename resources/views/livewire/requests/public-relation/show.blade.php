<div>
    @can('view', $this->publicRelation)
        <div x-data="{
                partTab: '{{ $this->publicRelation->documentUploads->first()->part_number ?? '' }}',
                mode: ''
            }" class="overflow-x-auto">
            <flux:button :href="route('pr.index')" icon="arrow-long-left" variant="subtle">Kembali ke Tabel</flux:button>

            <flux:heading size="xl" class="p-4">Detail Permohonan Layanan</flux:heading>

            <x-layouts.requests.show overViewRoute='pr.show' activityRoute="pr.activity" :id="$publicRelationId">
                <x-layouts.requests.info-responsible-person :requestable="$publicRelation" />
               
                <div class="mt-3 mr-3">
                    @foreach ($publicRelation->documentUploads as $documentUpload)
                        <div x-show="partTab === '{{ $documentUpload->part_number }}'" x-cloak>
                            <iframe loading="lazy" src="{{ $this->getFileUrl($documentUpload) }}" width="100%"
                                height="800" class="rounded-lg shadow border-none">
                                <a href="{{ $this->getFileUrl($documentUpload) }}">Download PDF</a>
                            </iframe>
                        </div>
                    @endforeach
                </div>

                <livewire:requests.public-relation.actions-modal :publicRelationId="$publicRelationId"
                    :publicRelationRequest="$publicRelation" />

                <x-slot name="rightSidebar">
                    <h3 class="text-lg font-bold mb-4">General</h3>

                    <x-layouts.requests.public-relation.right-sidebar-content :publicRelationId="$publicRelationId"
                        :publicRelation="$publicRelation" />
                </x-slot>
            </x-layouts.requests.show>
        </div>
    @endcan
</div>