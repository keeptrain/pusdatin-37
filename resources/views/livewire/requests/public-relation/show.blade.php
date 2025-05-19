<div>
    <div x-data="{
        partTab: '{{ $this->publicRelations->documentUploads->first()->part_number ?? '' }}',
    }"
                class="overflow-x-auto">
        <flux:button :href="route('pr.index')" icon="arrow-long-left" variant="subtle">Back to Table</flux:button>

        <x-letters.detail-layout :letterId="$publicRelationId">
            @forelse ($publicRelations->documentUploads as $documentUpload)
            <div x-show="partTab === '{{ $documentUpload->part_number }}'" class="p-6">
                <iframe loading="lazy" src="{{ asset($documentUpload->activeVersion->file_path) }}" width="100%"
                    height="800" class="rounded shadow border-none">
                </iframe>
            </div>
            @empty
                <p>Tidak ada dokumen yang diunggah untuk permintaan ini.</p>
            @endforelse
          
            <livewire:requests.public-relation.confirm-modal :documentUploads="$this->documentUploads" />

            <x-slot name="rightSidebar">
                <h3 class="text-lg font-bold mb-4">General</h3>

                <div x-data="{ status: '{{ $this->publicRelations->status->label() }}' }" class="space-y-6">
                    <h4 class="text-gray-500 mb-1">Tema</h4>
                    <p class="text-gray-800">
                        {{ $this->publicRelations->theme }}
                    </p>

                    <h4 class="text-gray-500 mb-1">Target</h4>
                    <p class="text-gray-800">
                        {{ $this->publicRelations->target }}
                    </p>

                    <h4 class="text-gray-500 mb-1">Penanggung Jawab</h4>
                    <p class="text-gray-800">
                        {{ $this->publicRelations->responsible_person }}
                    </p>

                    <h4 class="text-gray-500 mb-1">Kontak</h4>
                    <p class="text-gray-800">
                        {{ $this->publicRelations->contact }}
                    </p>

                    <h4 class="text-gray-500 mb-1">Seksi</h4>
                    <p class="text-gray-800">
                        {{ $this->publicRelations->section }}
                    </p>

                    <h4 class="text-gray-500 mb-1">Bulan Publikasi</h4>
                    <p class="text-gray-800">
                        {{ $this->publicRelations->month_publication }}
                    </p>

                    <h4 class="text-gray-500 mb-1">Tanggal Spesifik Publikasi Media</h4>
                    <p class="text-gray-800">
                        {{ $this->publicRelations->spesific_date }}
                    </p>
                    
                    <h4 class="text-gray-500 mb-1">Jenis media</h4>
                    <template x-for="part in {{ json_encode($this->publicRelations->documentUploads->toArray()) }}">
                        <section>
                            <li x-text="part.part_number_label"></li>
                        </section>
                    </template>

                    <h4 class="text-gray-500 mb-1">Status</h4>
                    <p class="text-gray-800" x-text="status"/>
        
                    <div class="border-1 p-3">
                        <h4 class="text-gray-500 mb-3">Materi</h4>
                        <div class="space-y-3">
                            @foreach ($this->publicRelations->documentUploads as $documentUpload)
                                <div class="flex items-center">
                                    <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center mr-2">
                                    <flux:icon.document-magnifying-glass class="size-4"/>
                                    </div>
                                    <button @click="partTab = '{{ $documentUpload->part_number }}'" class="text-gray-800 cursor-pointer"
                                        :class="{'border-b-2 border-blue-500 text-blue-600': partTab === '{{ $documentUpload->part_number }}' }">{{ $documentUpload->part_number_label }}</button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex flex-1 gap-2 mt-6">
                        <template x-if="status === 'Antrean Promkes'">
                            <flux:modal.trigger name="curation-modal" >
                                <flux:button x-on:click="$dispatch('modal-show', { name: 'curation-modal' })" variant="primary" icon="pencil-square" class="w-full" >
                                    {{ __('Kurasi') }}
                                </flux:button>
                            </flux:modal.trigger>
                        </template>

                        <template x-if="status === 'Kurasi Promkes'">
                            <flux:modal.trigger name="process-modal" >
                                <flux:button x-on:click="$dispatch('modal-show', { name: 'process-modal' })" variant="primary" icon:trailing="arrow-right" class="w-full" >
                                    {{ __('Proses') }}
                                </flux:button>
                            </flux:modal.trigger>
                        </template>

                        <template x-if="status === 'Proses pusdatin'">
                            <flux:modal.trigger name="completed-modal" >
                                <flux:button x-on:click="$dispatch('modal-show', { name: 'completed-modal' })" variant="primary" class="w-full" >
                                    {{ __('Selesaikan') }}
                                </flux:button>
                            </flux:modal.trigger>
                        </template>
                        <flux:dropdown>
                            <flux:button icon="ellipsis-horizontal"/>
                            <flux:menu>
                                <flux:menu.item :href="route('letter.edit', [$publicRelationId])" icon="pencil-square">Force edit</flux:menu.item>
                                <flux:menu.item :href="route('letter.rollback', [$publicRelationId])" icon="backward">Rollback</flux:menu.item>
                                <flux:menu.item icon="trash" variant="danger">Delete</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </div>
                </div>

            </x-slot>
           
            
        </x-letters.detail-layout>
    </div>
</div>