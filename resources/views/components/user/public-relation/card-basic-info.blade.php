<div class="max-w-screen-xl mx-auto px-4 lg:px-0 mb-6">
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <!-- Header: Request ID & Status -->
        <div class="px-6 py-4 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Tema: {{ $prRequest->theme }}
                    <h3>
                        <p class="text-gray-500 text-sm mb-1">Sasaran: {{ ucfirst($prRequest->target) }}</p>
            </div>
            <div class="mt-4 md:mt-0">
                <flux:notification.status-badge :status="$prRequest->status" />
            </div>
        </div>

        <!-- Body: Basic Info -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Nama Penanggung Jawab</p>
                    <p class="mt-1 text-gray-900">{{ $prRequest->user->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Jenis Layanan</p>
                    <p class="mt-1 text-gray-900">Kehumasan</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Kontak Penanggung Jawab</p>
                    <p class="mt-1 text-gray-900">{{ $prRequest->user->contact }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Tanggal diajukan</p>
                    <p class="mt-1 text-gray-900">{{ $prRequest->created_at }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Seksi</p>
                    <p class="mt-1 text-gray-900">{{ ucfirst($prRequest->user->section) }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase mb-1">Materi yang di upload</p>
                    <div x-data="{
                        hasPartNumber3: {{ $this->uploadedFile->contains(fn($file) => $file['part_number'] == 3) ? 'true' : 'false' }}
                        }">
                        @foreach ($this->uploadedFile as $file)
                                            <div class="flex flex-row gap-2">
                                                <x-lucide-circle-check-big class="w-4 text-green-500" />
                                                <a href="#" wire:click.prevent="downloadFile('{{ $file['part_number'] }}')"
                                                    class="hover:text-zinc-700 hover:underline cursor-pointer">{{ $file['part_number_label']
                            ?? $file['part_number'] }}</a>
                                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Bulan publikasi</p>
                    <p class="mt-1 text-gray-900">{{ ucfirst($prRequest->month_publication) }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Link Produksi</p>
                    @if ($prRequest->links)
                        @foreach ($prRequest->links as $key => $value)
                            @php
                                $label = match ($key) {
                                    1 => 'Audio',
                                    2 => 'Infografis',
                                    3 => 'Poster',
                                    4 => 'Media',
                                    5 => 'Bumper',
                                    6 => 'Backdrop Kegiatan',
                                    7 => 'Spanduk',
                                    8 => 'Roll Banner',
                                    9 => 'Sertifikat',
                                    10 => 'Press Release',
                                    11 => 'Artikel',
                                }
                            @endphp
                            <li>
                                <a href="{{ $value }}" class="font-semibold text-blue-800">{{ $label }}</a>
                            </li>
                        @endforeach
                    @else
                        <span>-</span>
                    @endif
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Tanggal Spesifik Publikasi Media</p>
                    <p class="mt-1 text-gray-900">{{ ucfirst($prRequest->spesific_date) }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Link Publikasi</p>
                    <p class="mt-1 text-gray-900">-</p>
                </div>
            </div>
        </div>
    </div>
</div>