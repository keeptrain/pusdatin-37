<div x-data="{ mode: null, partTab: '{{ $systemRequest->documentUploads->first()->part_number ?? '' }}' }"
    class="overflow-x-auto">
    <flux:button :href="route('is.index')" icon="arrow-long-left" variant="subtle">Kembali ke Tabel</flux:button>

    <flux:heading size="lg" class="p-4">Detail Permohonan Layanan</flux:heading>

    <x-layouts.requests.show overViewRoute="is.show" activityRoute="is.activity" :id="$systemRequestId">
        <div class="flex-1 p-4 md:p-3">
            <div class="mt-3 mr-3">
                @foreach ($systemRequest->documentUploads as $fileData)
                    <div x-show="partTab === '{{ $fileData['part_number'] }}'" x-cloak>
                        <iframe loading="lazy" src="{{ asset($fileData->activeVersion->file_path)}}" width="100%"
                            height="800" class="rounded-lg shadow border-none">
                            This browser does not support PDFs. Please download the PDF to view it:
                            <a href="{{ asset($fileData->activeVersion->file_path)}}">Download PDF</a>
                        </iframe>
                    </div>
                @endforeach
            </div>

            <x-slot name="rightSidebar">
                <h3 class="text-lg font-bold mb-4">General</h3>

                <x-layouts.requests.information-system.right-sidebar-content :systemRequest="$systemRequest" />

                <div class="mt-6">
                    <x-menu.information-system.actions-buttons-on-show :systemRequestId="$systemRequestId"
                        :systemRequest="$systemRequest" />
                </div>

                <x-menu.dropdown-menu-on-show :systemRequestId="$systemRequestId" />
            </x-slot>

            <livewire:letters.modal-confirmation :systemRequestId="$systemRequestId"
                :allowedParts="$this->allowedParts" />
        </div>
    </x-layouts.requests.show>
</div>