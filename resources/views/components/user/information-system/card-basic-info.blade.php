<div class="max-w-screen-xl mx-auto px-4 lg:px-0 mb-6">
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <!-- Header: Request ID & Status -->
        <div class="px-6 py-4 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Judul: {{ $title }}</h3>
                <p class="text-gray-500 text-sm mb-1">Nomor Surat: {{$referenceNumber}}</p>
            </div>
            <div class="mt-4 md:mt-0">
                <flux:notification.status-badge :status="$status" />
            </div>
        </div>

        <!-- Body: Basic Info -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Nama Penanggung Jawab</p>
                    <p class="mt-1 text-gray-900">{{ $person }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Jenis Layanan</p>
                    <p class="mt-1 text-gray-900">Sistem Informasi & Data</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Seksi</p>
                    <p class="mt-1 text-gray-900">Pelayanan kesehatan</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Tanggal diajukan</p>
                    <p class="mt-1 text-gray-900">{{ $createdAt }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Kontak</p>
                    <p class="mt-1 text-gray-900">{{ $contact }}</p>

                    @if (isset($this->meeting) && count($this->meeting))
                        <p class="text-xs font-medium text-gray-500 uppercase mt-4">Meeting</p>

                        {{-- Tampilkan meeting terdekat --}}
                        <x-user.information-system.meeting-info :meeting="$this->meeting[0]" />
                    @else
                        {{-- <p class="text-xs font-medium text-gray-500 uppercase mt-2">Belum ada meeting</p> --}}
                    @endif
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase mb-1">File yang di upload</p>
                    <div x-data="{
                        hasPartNumber5: {{ $this->uploadedFile->contains(fn($file) => $file['part_number'] == 5) ? 'true' : 'false' }}
                    }">
                        @foreach ($this->uploadedFile as $file)
                            <div class="flex flex-row gap-2">
                                <x-lucide-circle-check-big class="w-4 text-green-500" />
                                <a href="#" wire:click.prevent="downloadFile('{{ $file['part_number'] }}')"
                                    class="hover:text-zinc-700 hover:underline cursor-pointer">{{ $file['part_number_label'] ?? $file['part_number'] }}</a>
                            </div>
                        @endforeach

                        <template x-if="!hasPartNumber5">
                            <flux:modal.trigger name="upload-modal">
                                <a x-on:click="$dispatch('modal-show', { name: 'upload-modal' })"
                                    class="flex gap-2 mt-4 text-blue-900 hover:underline cursor-pointer " type="file">
                                    <x-lucide-upload class="w-4" />
                                    Upload file pendukung
                                </a>
                            </flux:modal.trigger>
                        </template>
                    </div>
                </div>
            </div>

            <div>
                <div class="flex justify-end">
                    <template x-if="{{ $activerevision }}">
                        <flux:button href="{{ route('letter.edit', [$id]) }}">Lakukan revisi</flux:button>
                    </template>
                </div>
            </div>
        </div>
    </div>
    <x-modal.additional-file-upload :title="$title" />
</div>