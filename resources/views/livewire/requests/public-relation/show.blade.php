<div>
    @can('view', $this->publicRelation)
    <div x-data="{
        partTab: '{{ $this->publicRelation->documentUploads->first()->part_number ?? '' }}',
        mode: ''
    }"
        class="overflow-x-auto">
        <flux:button :href="route('pr.index')" icon="arrow-long-left" variant="subtle">Kembali ke Tabel</flux:button>

        <flux:heading size="xl" class="p-4">Detail Permohonan Layanan</flux:heading>

        <x-layouts.requests.show overViewRoute='pr.show' activityRoute="pr.activity" :id="$publicRelationId">
            @forelse ($publicRelation->documentUploads as $documentUpload)
            <div x-show="partTab === '{{ $documentUpload->part_number }}'" class="p-6">
                <iframe loading="lazy" src="{{ asset($documentUpload->activeVersion->file_path) }}" width="100%"
                    height="800" class="rounded shadow border-none">
                </iframe>
            </div>
            @empty
                <p>Tidak ada dokumen yang diunggah untuk permintaan ini.</p>
            @endforelse

            <livewire:requests.public-relation.actions-modal :publicRelationId="$publicRelationId" :publicRelationRequest="$publicRelation" />

            <x-slot name="rightSidebar">
                <h3 class="text-lg font-bold mb-4">General</h3>

                <x-layouts.requests.public-relation.right-sidebar-content :publicRelationId="$publicRelationId" :publicRelation="$publicRelation"/>
            </x-slot>
        </x-layouts.requests.show>
    </div>
    @endcan
</div>
