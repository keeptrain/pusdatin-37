<div class="lg:p-3">
    <flux:heading size="xl" level="1">{{ __('Rating') }}</flux:heading>
    <flux:heading size="lg" level="2" class="mb-6">{{ __('Daftar rating yang diberikan oleh pemohon') }}</flux:heading>

    <div class="space-y-4">
        {{-- Header --}}
        <div class="flex flex-1 justify-between items-center">
            <div class="flex">
                <flux:text class="w-full">Total Rating: 205</flux:text>
                {{-- <flux:select size="sm" placeholder="Pilih Bulan">
                    <flux:select.option value="bulan">Bulan</flux:select.option>
                    <flux:select.option value="tahun">Januari</flux:select.option>
                </flux:select> --}}
            </div>
            <flux:button wire:click="replyToAllGivesRating" size="sm" icon="inbox-stack">Balas Semua</flux:button>
        </div>

        <div>
            <div
                class="hidden md:grid grid-cols-14 gap-4 p-4 bg-gray-50 border rounded-t-xl text-xs font-medium text-gray-700 uppercase tracking-wide">
                <div class="col-span-5">Pemohon & Permohonan</div>
                <div class="col-span-4 flex items-center">
                    <div class="col-span-4 flex items-center">
                        <button wire:click="sortRating('{{ $sortDirection === 'asc' ? 'desc' : 'asc' }}')"
                            class="flex items-center uppercase">
                            <flux:icon.chevron-up-down class="size-5 mr-2 cursor-pointer" />
                            Ratings & Komentar
                            @if($sortDirection === 'asc')
                                <span class="ml-1 text-xs">(↓)</span>
                            @else
                                <span class="ml-1 text-xs">(↑)</span>
                            @endif
                        </button>
                    </div>
                </div>
                <div class="col-span-3">Tanggal Rating</div>
                <div class="col-span-2">Aksi</div>
            </div>

            {{-- Reviews --}}
            <div class="divide-y border-l border-r border-b">
                @foreach ($contents as $item)
                    @if (isset($item->rating))
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="grid md:grid-cols-14 gap-4">
                                {{-- Pemohon & Permohonan --}}
                                <div class="md:col-span-5 flex items-start gap-3">
                                    <flux:avatar size="lg" src="https://cdn-icons-png.flaticon.com/512/5556/5556468.png"
                                        class="flex-shrink-0" />
                                    <div class="min-w-0">
                                        <flux:legend class="font-normal text-gray-900 text-base md:text-lg">
                                            {{ $item->user->name }}
                                        </flux:legend>
                                        <flux:text class="text-gray-600 text-sm md:flex items-center">
                                            @if ($item->title) Judul: @else Tema: @endif
                                            <span class="font-semibold text-gray-900 md:truncate max-w-[300px]"
                                                title="{{ $item->theme ?? $item->title }}">
                                                {{ $item->theme ?? $item->title }}
                                        </flux:text>
                                    </div>
                                </div>

                                {{-- Ratings --}}
                                <div class="md:col-span-4 space-y-2">
                                    <x-rating-emoticon :key="$item->rating['rating']" />
                                    <flux:text class="text-sm text-gray-700 leading-relaxed">
                                        {{ $item->rating['comment'] ?: 'Tidak ada komentar tersedia untuk review ini.' }}
                                    </flux:text>
                                </div>

                                {{-- Tanggal --}}
                                <div class="md:col-span-3 flex items-center text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($item->rating['rating_date'])->format('d M Y , H:i') }}
                                </div>

                                {{-- Aksi --}}
                                <div class="md:col-span-2 flex items-center">
                                    <flux:button
                                        href="{{ ($item->current_division == 3 || $item->current_division == 5) ? route('is.show', [$item->id]) : route('pr.show', [$item->id]) }}"
                                        icon="arrow-top-right-on-square" variant="ghost" inset wire:navigate>
                                    </flux:button>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>