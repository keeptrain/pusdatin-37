<div class="lg:p-3">
    <flux:heading size="xl" level="1">{{ __('Rating') }}</flux:heading>
    <flux:heading size="lg" level="2" class="mb-6">{{ __('Daftar rating yang diberikan oleh pemohon') }}</flux:heading>

    <!-- Header -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <flux:text class="w-full">Total Rating: 205</flux:text>
            <flux:select size="sm">
                <flux:select.option value="bulan">Bulan</flux:select.option>
            </flux:select>
        </div>

        {{-- <!-- Rating Section -->
        <div class="flex justify-between items-center space-x-2">
            <template x-for="(rating, index) in ratings" :key="index">
                <div x-on:click="selectedRating = index; tempRating = ratings[index].value"
                    :class="selectedRating === index ? 'bg-white border-blue-500 shadow-md shadow-blue-100' : 'bg-white border-gray-200'"
                    class="p-6 w-60 flex flex-col items-center border rounded-lg cursor-pointer">

                    <!-- Angka di Samping Kiri Emoticon -->
                    <div class="flex items-start space-x-4">
                        <div class="flex flex-col items-center">
                            <flux:heading size="xl" x-text="rating.value"></flux:heading>
                            <span class="text-xs text-gray-600" x-text="rating.label"></span>
                        </div>
                        <button type="button"
                            class="w-12 h-12 rounded-full flex items-center justify-center transition-colors duration-200 mb-2">
                            <svg class="w-12 h-12" :class="selectedRating === index ? 'text-blue-500' : 'text-gray-600'"
                                fill="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1" fill="none" />
                                <!-- Bad -->
                                <g x-show="index === 0">
                                    <circle cx="8" cy="9" r="1.5" />
                                    <circle cx="16" cy="9" r="1.5" />
                                    <path d="M8 16s1.5-2 4-2 4 2 4 2" stroke="currentColor" stroke-width="1.5"
                                        fill="none" />
                                </g>
                                <!-- Okay -->
                                <g x-show="index === 1">
                                    <circle cx="8" cy="9" r="1.5" />
                                    <circle cx="16" cy="9" r="1.5" />
                                    <line x1="8" y1="15" x2="16" y2="15" stroke="currentColor" stroke-width="1.5" />
                                </g>
                                <!-- Good -->
                                <g x-show="index === 2">
                                    <circle cx="8" cy="9" r="1.5" />
                                    <circle cx="16" cy="9" r="1.5" />
                                    <path d="M8 14s1.5 2 4 2 4-2 4-2" stroke="currentColor" stroke-width="1.5"
                                        fill="none" />
                                </g>
                                <!-- Amazing -->
                                <g x-show="index === 3">
                                    <circle cx="8" cy="9" r="1.5" />
                                    <circle cx="16" cy="9" r="1.5" />
                                    <path d="M7 13s1.5 3 5 3 5-3 5-3" stroke="currentColor" stroke-width="1.5"
                                        fill="none" />
                                </g>
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div> --}}

        <div>
            <!-- Column Headers -->
            <div
                class="grid grid-cols-14 gap-4 p-4 bg-gray-50 border-t border-l border-r rounded-t-xl text-xs font-medium text-gray-700 uppercase tracking-wide">
                <div class="col-span-5">
                    <flux:text>Pemohon & Permohonan</flux:text>
                </div>
                <div class="col-span-4">
                    <flux:text class="flex items-center"><flux:icon.chevron-up-down class="size-5 mr-2" /> Ratings &
                        Komentar</flux:text>
                </div>
                <div class="col-span-3">
                    <flux:text>Tanggal Rating</flux:text>
                </div>
                <div class="col-span-2">
                    <flux:text>Aksi</flux:text>
                </div>
            </div>

            <!-- Reviews List -->
            <div class="border divide-y">
                @foreach ($contents as $item)
                    @if (isset($item->rating))
                        <div class="grid grid-cols-14 gap-4 p-4 rounded-t-xl hover:bg-gray-50 transition-colors">
                            <!-- Student & Course Info -->
                            <div class="col-span-5">
                                <div class="flex items-start space-x-3">
                                    <flux:avatar size="lg" src="https://cdn-icons-png.flaticon.com/512/5556/5556468.png"
                                        class="flex-shrink-0" />
                                    <div class="min-w-0">
                                        <flux:legend class="font-normal text-gray-900 text-lg">
                                            {{ $item->user->name }}
                                        </flux:legend>
                                        <flux:text class="text-gray-600 text-sm flex items-center">
                                            @if ($item->title) Judul - @else Tema - @endif
                                            <span class="font-semibold text-gray-900 truncate max-w-[300px] ml-2"
                                                title="{{ $item->theme ?? $item->title }}">
                                                {{ $item->theme ?? $item->title }}
                                            </span>
                                        </flux:text>
                                    </div>
                                </div>
                            </div>

                            <!-- Ratings & Comments -->
                            <div class="col-span-4">
                                @if (isset($item->rating))
                                    @foreach ($item->rating as $key => $value)
                                        @php
                                            $rating = match ($key) {
                                                1 => 'Bad',
                                                2 => 'Okay',
                                                3 => 'Good',
                                                4 => 'Amazing',
                                            };
                                        @endphp
                                        <div>
                                            <div class="flex items-center space-x-2 mb-1">
                                                <x-rating-emoticon :key="$key" />

                                                <flux:text class="font-semibold text-gray-900">{{ $rating }}</flux:text>
                                            </div>

                                            <!-- Comment -->
                                            <flux:text class="text-gray-700 text-sm leading-relaxed">
                                                {{ $value ?: 'Tidak ada komentar tersedia untuk review ini.' }}
                                            </flux:text>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex items-center space-x-2 mb-2">
                                        <flux:icon name="star" class="w-4 h-4 text-yellow-400 fill-current" />
                                        <flux:text class="font-semibold text-gray-900">4.0</flux:text>
                                    </div>
                                    <flux:text class="text-gray-700 text-sm leading-relaxed">
                                        Tidak ada komentar tersedia untuk review ini.
                                    </flux:text>
                                @endif
                            </div>

                            <!-- Posted Date -->
                            <div class="col-span-3 flex items-center h-full">
                                <flux:text class="text-gray-600 text-sm">
                                    {{ \Carbon\Carbon::parse($item->completed_date)->format('d M, Y') }}
                                </flux:text>
                            </div>

                            <!-- Action -->
                            <div class="col-span-2 flex items-center h-full">
                                @if ($key <= 2)
                                    <flux:button size="sm" variant="ghost"><x-lucide-reply class="w-4 h-4 text-gray-500" />
                                        <span class="text-gray-500">Balas</span>
                                    </flux:button>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>