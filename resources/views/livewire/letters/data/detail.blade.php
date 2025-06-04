@php
    $status = $letter->status->label();
@endphp

<div class="overflow-x-auto">
    <flux:button :href="route('letter.table')" icon="arrow-long-left" variant="subtle">Back to Table</flux:button>

    <div x-data="{ partTab: '{{ $letter->documentUploads->first()->part_number ?? '' }}' }">

        <flux:heading size="xl" class="p-4">Detail Permohonan Layanan</flux:heading>
        
        <x-letters.detail-layout overViewRoute="letter.detail" activityRoute="letter.activity" :id="$letterId">
            <div class="mt-3 mr-3">

                @foreach ($letter->documentUploads as $fileData)
                    <div x-show="partTab === '{{ $fileData['part_number'] }}'" x-cloak>
                        <iframe loading="lazy" src="{{ asset($fileData->activeVersion->file_path)}}" width="100%" height="800"
                            class="rounded-lg shadow border-none">
                            This browser does not support PDFs. Please download the PDF to view it:
                            <a href="{{ asset($fileData->activeVersion->file_path)}}">Download PDF</a>
                        </iframe>
                    </div>
                 @endforeach
                
                <livewire:letters.modal-confirmation :letterId="$letterId" :availablePart="$this->availablePart" />
            </div>

            <x-slot name="rightSidebar">
                <h3 class="text-lg font-bold mb-4">General</h3>

                <div class="mb-6">
                    <h4 class="text-gray-500 mb-1">Judul</h4>
                    <p class="text-gray-800">
                        {{ $letter->title }}
                    </p>
                </div>

                <div class="mb-6">
                    <h4 class="text-gray-500 mb-1">Penanggung Jawab</h4>
                    <p class="text-gray-800">
                        {{ $letter->user->name }}
                    </p>
                </div>
                
                <div class="mb-6">
                    <h4 class="text-gray-500 mb-1">Nomor Surat</h4>
                    <p class="text-gray-800">
                        {{ $letter->reference_number }}
                    </p>
                </div>

                <div class="mb-6">
                    <h4 class="text-gray-500 mb-1">Kontak</h4>
                    <p class="text-gray-800">
                        {{ $letter->user->contact }}
                    </p>
                </div>

                <div class="mb-6">
                    <h4 class="text-gray-500 mb-1">Seksi</h4>
                    <p class="text-gray-800">
                        {{ $letter->user->section }}
                    </p>
                </div>

                <div class="mb-6">
                    <h4 class="text-gray-500 mb-1">Tanggal dibuat</h4>
                    <p class="text-gray-800">{{ $letter->createdAtWithTime() }}</p>
                </div>

                <div class="mb-6">
                    <h4 class="text-gray-500 mb-1">Update terakhir</h4>
                    <p class="text-gray-800">{{ $letter->updated_at }}</p>
                </div>

                @if ($letter->meeting)
                <div class="mb-6">
                    <h4 class="  text-gray-500 mb-1 flex items-center">
                        Meeting
                    </h4>
                    <x-menu.information-system.meeting-details-on-show :meeting="$letter->meeting" :date="$letter->getFormattedMeetingDate()" />
                </div>
                    
                @endif

                <div class="mb-6">
                    <h4 class="text-gray-500 mb-1">Status</h4>
                    <flux:notification.status-badge :status="$letter->status"/>
                </div>

                <div class="border-1 rounded-lg p-3">
                    <h4 class="text-gray-500 mb-3">Documents</h4>
                    <div class="space-y-3">
                        @foreach ($letter->documentUploads as $file)
                            <div class="flex items-center">
                                <flux:icon.document class="size-5 mr-3"/>
                                <button @click="partTab = '{{ $file->part_number }}'" class="text-gray-800 cursor-pointer"
                                    :class="{'border-b-2 border-blue-600 ': partTab === '{{ $file->part_number }}' }">{{ $file->part_number_label }}</button>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="mt-6">
                    <x-menu.information-system.actions-buttons-on-show :letter="$letter" :letterId="$letterId"/>
                </div>

                <x-menu.dropdown-menu-on-show :letterId="$letterId"/>
            </x-slot>
        </x-letters.detail-layout>
    </div>
</div>
