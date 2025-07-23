<div>
    <flux:heading size="xl" level="1">{{ __('Rating') }}</flux:heading>
    <flux:heading size="lg" level="2" class="mb-6">{{ __('Daftar rating yang diberikan oleh pemohon') }}</flux:heading>

    <div class="space-y-4">
        {{-- Header --}}
        @if ($ratingCount > 0)
            <div class="flex flex-1 justify-between items-center">
                <div class="flex">
                    <flux:text class="w-full">Total Rating: {{ $ratingCount}}</flux:text>
                </div>
                <flux:button wire:click="replyToAllGivesRating" size="sm" icon="inbox-stack">Balas Semua</flux:button>
            </div>

            <div>
                <div
                    class="hidden md:grid grid-cols-14 gap-4 p-4 bg-gray-50 border rounded-t-xl text-xs font-medium text-gray-700 uppercase tracking-wide">
                    <div class="col-span-5">Pemohon & Permohonan</div>
                    <div class="col-span-6 flex items-center">
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
                    <div class="col-span-2">Tanggal Rating</div>
                    <div class="col-span-1">Aksi</div>
                </div>

                {{-- Reviews --}}
                <div class="divide-y border-l border-r border-b">
                    @foreach ($contents as $item)
                        @if (isset($item->rating))
                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                <div class="grid md:grid-cols-14 gap-4">
                                    {{-- Pemohon & Permohonan --}}
                                    <div class="md:col-span-5 flex items-start gap-3">

                                        <flux:avatar size="lg" :initials="$item->user->initials()" class="flex-shrink-0" />
                                        <div class="min-w-0 space-y-2">
                                            <flux:heading>
                                                {{ $item->user->name }}

                                            </flux:heading>
                                            <flux:text class="text-gray-600 text-sm md:flex items-center gap-1">
                                                @if ($item->title) Judul: @else Tema: @endif
                                                <span class=" text-gray-900 md:truncate max-w-[300px]"
                                                    title="{{ $item->theme ?? $item->title }}">
                                                    {{ $item->theme ?? $item->title }}
                                            </flux:text>
                                        </div>
                                    </div>

                                    {{-- Ratings --}}
                                    <div class="md:col-span-6 flex items-center gap-1">
                                        <x-rating-emoticon :key="$item->rating['rating']" />
                                        <flux:legend>
                                            : {{ $item->rating['comment'] ?: 'Tidak ada komentar tersedia untuk review ini.' }}
                                        </flux:legend>
                                    </div>

                                    {{-- Tanggal --}}
                                    <div class="md:col-span-2 flex items-center text-sm text-gray-600 gap-2">
                                        {{ \Carbon\Carbon::parse($item->rating['rating_date'])->format('d M Y') }}
                                        @if ($item->rating['replied_at'])
                                            <x-lucide-circle-check-big class="w-4 text-green-500" />
                                        @endif
                                    </div>

                                    {{-- Aksi --}}
                                    <div class="md:col-span-1 flex items-center">
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
        @else
            <div class="flex flex-col items-center justify-center h-[calc(80vh-12rem)] space-y-4">
                <flux:icon.sparkles class="mx-auto size-15 text-amber-500 dark:text-amber-300" />
                <flux:text class="text-center">Belum ada rating yang diberikan oleh pemohon.</flux:text>
            </div>
        @endif
    </div>
</div>