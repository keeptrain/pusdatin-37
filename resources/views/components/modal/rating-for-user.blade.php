<flux:modal name="rating-modal" class="bg-gray-50">
    <form wire:submit="submitRating" x-data="{
        selectedRating: null,
        tempRating: null,
        tempComment: '',
        ratings: [
            { label: 'Bad', value: 1 },
            { label: 'Okay', value: 2 },
            { label: 'Good', value: 3 },
            { label: 'Amazing', value: 4 }
        ],
        prepareSubmit() {
            // Kirim data ke Livewire sebelum submit
            $wire.set('rating.value', this.tempRating, false);
            $wire.set('rating.comment', this.tempComment, false);
        }
    }">
        <!-- Header -->
        <div class="space-y-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Rating</h2>
                <p class="text-sm text-gray-600 mt-1">Beri rating terhadap hasil permohonan ini</p>
            </div>

            <!-- Rating Section -->
            <div class="mb-6">
                <div class="flex justify-between items-center space-x-2">
                    <template x-for="(rating, index) in ratings" :key="index">
                        <div x-on:click="selectedRating = index; tempRating = ratings[index].value" 
                            :class="selectedRating === index ? 'bg-white border-blue-500 shadow-md shadow-blue-100' : 'bg-white border-gray-200'"
                            class="p-6 flex flex-col items-center border cursor-pointer">
                            <button type="button" class="w-12 h-12 rounded-full flex items-center justify-center transition-colors duration-200 mb-2">
                                <svg class="w-12 h-12" :class="selectedRating === index ? 'text-blue-500' : 'text-gray-600'" fill="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1" fill="none" />
                                    {{-- <!-- Terrible -->
                                    <g x-show="index === 0">
                                        <circle cx="8" cy="9" r="1.5" />
                                        <circle cx="16" cy="9" r="1.5" />
                                        <path d="M16 16s-1.5-2-4-2-4 2-4 2" stroke="currentColor" stroke-width="1.5"
                                            fill="none" />
                                    </g> --}}
                                    <!-- Bad -->
                                    <g x-show="index === 0" >
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
                            <span class="text-xs text-gray-600" x-text="rating.label"></span>
                        </div>
                    </template>
                </div>
            </div>
            
            <!-- Comment Section -->
            <div class="mb-6">
                <flux:textarea label="Apa alasan utama untuk penilaian Anda?" x-model="tempComment" wire:model="rating.comment" placeholder="Masukkan alasan..." />
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3">
                <flux:button type="submit" @click="prepareSubmit()" x-bind:disabled="selectedRating === null" x-bind:wire:loading.attr="selectedRating === null">Kirim</flux:button>
                {{-- <button type="submit" 
                    x-on:click="prepareSubmit()"
                    :disabled="selectedRating === null" 
                    :class="selectedRating !== null ? 'bg-blue-800 hover:bg-blue-900 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed'" 
                    class="py-2 px-4 rounded-md font-medium transition-colors duration-200">
                    Submit
                </button> --}}
            </div>
        </div>
    </form>
</flux:modal>