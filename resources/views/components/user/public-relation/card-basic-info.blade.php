<div class="max-w-screen-xl mx-auto lg:px-0 mb-3">
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <!-- Header: Request ID & Status -->
        <div class="px-6 py-4 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Tema: {{ $prRequest->theme }}
                    <h3>
                        <p class="text-gray-500 text-sm mb-1">Tanggal diajukan: {{ $prRequest->createdAtWithTime() }}</p>
            </div>
            <div class="mt-4 md:mt-0">
                <flux:notification.status-badge :status="$prRequest->status" />
            </div>
        </div>

        <!-- Body: Basic Info -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Penanggung Jawab</p>
                    <p class="mt-1 text-gray-900">{{ $prRequest->user->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Jenis Layanan</p>
                    <p class="mt-1 text-gray-900">Kehumasan</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Target</p>
                    @foreach ($prRequest->target as $target)
                        <li class="flex items-start text-gray-900">
                            <span class="mr-2">•</span>
                            <span>{{ $target }}</span>
                        </li>
                    @endforeach
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase mb-1">Materi yang di upload</p>
                    @foreach ($this->uploadedFile as $file)
                                    <div class="flex flex-row gap-2">
                                        <x-lucide-circle-check-big class="w-4 text-green-500" />
                                        <a href="#" wire:click.prevent="downloadFile('{{ $file['part_number'] }}')"
                                            class="hover:text-zinc-700 hover:underline cursor-pointer">{{ $file['part_number_label']
                        ?? $file['part_number'] }}
                                        </a>
                                    </div>
                    @endforeach
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Tanggal Selesai</p>
                    <p class="mt-1 text-gray-900">{{ ucfirst($prRequest->completed_date) }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Link Produksi</p>
                    @forelse ($this->linkProductions as $value)
                        <li>
                            <a href="{{ $value['url'] }}"
                                class="font-normal text-blue-800 hover:underline">{{ $value['label']}}</a>
                        </li>
                    @empty
                        <span>-</span>
                    @endforelse
                </div>
                {{-- <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Seksi</p>
                    <p class="mt-1 text-gray-900">{{ ucfirst($prRequest->user->section) }}</p>
                </div> --}}
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Bulan publikasi</p>
                    <p class="mt-1 text-gray-900">{{ ucfirst($prRequest->month_publication) }}</p>
                </div>
                <div>
                    {{-- <p class="text-xs font-medium text-gray-500 uppercase">Link Selesai</p>
                    <p class="mt-1 text-gray-900">-</p> --}}
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Tanggal Spesifik Publikasi Media</p>
                    <p class="mt-1 text-gray-900">{{ ucfirst($prRequest->spesific_date) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>