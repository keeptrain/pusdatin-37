<div class="space-y-4">
    <section>
        <h4 class="text-gray-500 mb-1">Status</h4>
        <flux:notification.status-badge :status="$publicRelation->status" />
    </section>

    <h4 class="text-gray-500 mb-1">Tema</h4>
    <p class="text-gray-800">
        {{ $publicRelation->theme }}
    </p>

    <section>
        <h4 class="text-gray-500 mb-1">Target</h4>
        <p class="text-gray-800">
            @foreach ($publicRelation->target as $target)
                <li class="flex items-start text-gray-900">
                    <span class="mr-2">•</span>
                <span>{{ $target }}</span>
            </li>
            @endforeach
        </p>
    </section>

    <h4 class="text-gray-500 mb-1">Penanggung Jawab</h4>
    <p class="text-gray-800">
        {{ $publicRelation->user->name }}
    </p>

    <h4 class="text-gray-500 mb-1">Kontak</h4>
    <p class="text-gray-800">
        {{ $publicRelation->user->contact }}
    </p>

    <h4 class="text-gray-500 mb-1">Seksi</h4>
    <p class="text-gray-800">
        {{ ucwords($publicRelation->user->section) }}
    </p>

    <h4 class="text-gray-500 mb-1">Bulan Publikasi</h4>
    <p class="text-gray-800">
        {{ $publicRelation->month_publication }}
    </p>

    <h4 class="text-gray-500 mb-1">Tanggal Spesifik Publikasi Media</h4>
    <p class="text-gray-800">
        {{ $publicRelation->spesificDate() }}
    </p>

    <h4 class="text-gray-500 mb-1">Tanggal Usulan Masuk</h4>
    <p class="text-gray-800">
        {{ $publicRelation->createdAtDMY() }}
    </p>

    <section>
        <h4 class="text-gray-500 mb-1">Jenis media</h4>
        <div x-data="{
            parts: {{ json_encode($this->getAllowedDocument()) }},
            links: {{ json_encode($publicRelation->links ?? []) }}
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

    <div class="border-1 rounded-lg p-3">
        <h4 class="text-gray-500 mb-3">Permohonan</h4>
        <div class="space-y-3">
            @foreach ($this->applicationLetter as $documentUpload)
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
        <x-menu.public-relation.actions-buttons-on-show :publicRelationRequest="$publicRelation" />
    </div>
    <flux:dropdown>
        <flux:button icon="ellipsis-horizontal" class="w-full"/>
        <flux:menu>
            {{-- <flux:menu.item :href="route('letter.edit', [$publicRelationId])" icon="pencil-square">Force edit</flux:menu.item> --}}
            <flux:menu.item :href="route('pr.rollback', [$publicRelationId])" icon="pencil-square">Edit</flux:menu.item>
            <flux:menu.item icon="trash" variant="danger">Delete</flux:menu.item>
        </flux:menu>
    </flux:dropdown>
</div>