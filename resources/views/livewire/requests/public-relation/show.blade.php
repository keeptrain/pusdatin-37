<div>
    @can('view', $this->publicRelation)
    <div x-data="{
        partTab: '{{ $this->publicRelation->documentUploads->first()->part_number ?? '' }}',
    }"
        class="overflow-x-auto">
        <flux:button :href="route('pr.index')" icon="arrow-long-left" variant="subtle">Back to Table</flux:button>

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
          
            <livewire:requests.public-relation.confirm-modal :publicRelationId="$publicRelationId" :publicRelationRequest="$this->publicRelation" />

            <x-slot name="rightSidebar">
                <h3 class="text-lg font-bold mb-4">General</h3>

                <div x-data="{ status: '{{ $this->publicRelation->status->label() }}', activeReview: '' }" class="space-y-4">
                    <h4 class="text-gray-500 mb-1">Tema</h4>
                    <p class="text-gray-800">
                        {{ $this->publicRelation->theme }}
                    </p>

                    <h4 class="text-gray-500 mb-1">Target</h4>
                    <p class="text-gray-800">
                        {{ $this->publicRelation->target }}
                    </p>

                    <h4 class="text-gray-500 mb-1">Penanggung Jawab</h4>
                    <p class="text-gray-800">
                        {{ $this->publicRelation->user->name }}
                    </p>

                    <h4 class="text-gray-500 mb-1">Kontak</h4>
                    <p class="text-gray-800">
                        {{ $this->publicRelation->user->contact }}
                    </p>

                    <h4 class="text-gray-500 mb-1">Seksi</h4>
                    <p class="text-gray-800">
                        {{ ucwords($this->publicRelation->user->section) }}
                    </p>

                    <h4 class="text-gray-500 mb-1">Bulan Publikasi</h4>
                    <p class="text-gray-800">
                        {{ $this->publicRelation->month_publication }}
                    </p>

                    <h4 class="text-gray-500 mb-1">Tanggal Spesifik Publikasi Media</h4>
                    <p class="text-gray-800">
                        {{ $this->publicRelation->spesificDate() }}
                    </p>

                    <h4 class="text-gray-500 mb-1">Tanggal Usulan Masuk</h4>
                    <p class="text-gray-800">
                        {{ $this->publicRelation->createdAtDMY() }}
                    </p>
                    
                    <section>
                        <h4 class="text-gray-500 mb-1">Jenis media</h4>
                        <div x-data="{ 
                            parts: {{ json_encode($this->getAllowedDocument()) }},
                            links: {{ json_encode($this->publicRelation->links ?? []) }}
                        }">
                            <template x-for="part in parts" :key="part.id">
                                <div class="flex items-center space-x-2 mb-2">
                                    <!-- Jika ada link -->
                                    <template x-if="links?.[part.part_number]">
                                        <div class="flex flex-1">
                                            <x-lucide-circle-check-big class="w-4 mr-3 text-green-500" />
                                            <a :href="links[part.part_number]" target="_blank" class="hover:underline">
                                                <span x-text="part.part_number_label"></span>
                                            </a>
                                        </div>
                                    </template>
                    
                                    <!-- Jika link null -->
                                    <template x-if="!links?.[part.part_number]">
                                        <div class="flex flex-1">
                                            <x-lucide-circle class="w-4 mr-3 text-gray-400" />
                                            <span x-text="part.part_number_label"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </section>

                    <section>
                        <h4 class="text-gray-500 mb-1">Status</h4>
                        <flux:notification.status-badge :status="$this->publicRelation->status" />
                    </section>

                    <div class="border-1 rounded-lg p-3">
                        <h4 class="text-gray-500 mb-3">Permohonan</h4>
                        <div class="space-y-3">
                            @foreach ($this->applicationLetter() as $documentUpload)
                                <div class="flex items-center">
                                    <flux:icon.document class="size-5 mr-3"/>
                                    <button @click="partTab = '{{ $documentUpload->part_number }}'" class="text-gray-800 cursor-pointer"
                                        :class="{'border-b-2 border-blue-500 text-blue-600': partTab === '{{ $documentUpload->part_number }}' }">{{ $documentUpload->part_number_label }}</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
        
                    <div class="border-1 rounded-lg p-3">
                        <h4 class="text-gray-500 mb-3">Materi</h4>
                        <div class="space-y-3">
                            {{-- @foreach ($allowedDocuments as $documentUpload)
                                <div class="flex items-center">
                                    <flux:icon.document class="size-5 mr-3"/>
                                    <button @click="partTab = '{{ $documentUpload['part_number'] }}'" class="text-gray-800 cursor-pointer"
                                        :class="{'border-b-2 border-blue-500 text-blue-600': partTab === '{{ $documentUpload['part_number'] }}' }">{{ $documentUpload['part_number_label'] }}</button>
                                </div>
                            @endforeach --}}
                            @foreach ($this->getAllowedDocument() as $documentUpload)
                                <div class="flex items-center">
                                    <flux:icon.document class="size-5 mr-3"/>
                                    <button @click="partTab = '{{ $documentUpload->part_number }}'" class="text-gray-800 cursor-pointer"
                                        :class="{'border-b-2 border-blue-500 text-blue-600': partTab === '{{ $documentUpload->part_number }}' }">{{ $documentUpload->part_number_label }}</button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6">
                        <x-menu.public-relation.actions-buttons-on-show :publicRelationRequest="$this->publicRelation" />
                    </div>
                    <flux:dropdown>
                        <flux:button icon="ellipsis-horizontal" class="w-full"/>
                        <flux:menu>
                            {{-- <flux:menu.item :href="route('letter.edit', [$publicRelationId])" icon="pencil-square">Force edit</flux:menu.item> --}}
                            <flux:menu.item :href="route('letter.rollback', [$publicRelationId])" icon="backward">Rollback</flux:menu.item>
                            <flux:menu.item icon="trash" variant="danger">Delete</flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </x-slot>
        </x-layouts.requests.show>
    </div>
    @endcan
</div>
