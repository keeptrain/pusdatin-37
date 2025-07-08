<div x-data="{ 
    selectedOption: '{{ $place['type'] }}',
    tempValues: {
        location: $wire.place.value,
        link: $wire.place.value,
        password: $wire.place.password
    },
    init() {
        // Inisialisasi nilai temporary
        this.tempValues.location = '{{ $place['type'] === 'location' ? $place['value'] : '' }}';
        this.tempValues.link = '{{ $place['type'] === 'link' ? $place['value'] : '' }}';
        this.tempValues.password = '{{ $place['type'] === 'link' ? ($place['password'] ?? '') : '' }}';
        
        // Update Livewire hanya saat form disubmit
        $watch('selectedOption', (newVal) => {
            $wire.set('place.type', newVal, false);
        });
    },
   
    prepareForSubmit() {
        // Sync temporary values with Livewire before submit
        if (this.selectedOption === 'location') {
            $wire.set('place.type', 'location');
            $wire.set('place.value', this.tempValues.location);
        } else {
            $wire.set('place.type', 'link');
            $wire.set('place.value', this.tempValues.link);
            $wire.set('place.password', this.tempValues.password);
        }
    }
}">
    <!-- Back button and header -->
    <flux:button :href="route('is.meeting', $systemRequestId)" icon="arrow-long-left" variant="subtle">
        Kembali
    </flux:button>

    <flux:heading size="lg" class="p-4">Ubah Meeting</flux:heading>
    
    <x-layouts.requests.show overViewRoute="is.show" activityRoute="is.activity" :id="$systemRequestId">
        <form wire:submit="update" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-4">
                @if (!$pastEndDate)
                <flux:heading size="md">Ubah tempat</flux:heading>
                <div class="grid grid-cols-1 gap-4">
                    <!-- Location option -->
                    <div @click="selectedOption = 'location'"
                        :class="selectedOption === 'location' ? 'border-zinc-300 bg-zinc-50' : 'border-gray-200 bg-white hover:border-gray-300 hover:bg-gray-50'"
                        class="relative p-4 rounded-lg border transition-all duration-200 cursor-pointer">
                        <!-- Radio button -->
                        <div class="absolute top-4 right-4">
                            <div :class="selectedOption === 'location' ? 'border-blue-500 bg-blue-500' : 'border-gray-300 bg-white'"
                                class="w-4 h-4 rounded-full border-2 flex items-center justify-center">
                                <div x-show="selectedOption === 'location'" class="w-2 h-2 rounded-full bg-white"></div>
                            </div>
                        </div>
                        <!-- Content -->
                        <div class="flex items-start mb-3">
                            <div :class="selectedOption === 'location' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600'"
                                class="p-4 rounded-md mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            </div>
                            <section>
                                <h3 :class="selectedOption === 'location' ? 'text-blue-900' : 'text-gray-900'"
                                    class="font-medium text-base">
                                    Pertemuan Langsung
                                </h3>
                                <p :class="selectedOption === 'location' ? 'text-blue-700' : 'text-gray-600'"
                                    class="text-sm">
                                    Bertemu secara langsung di lokasi
                                </p>
                            </section>
                        </div>
                    </div>
    
                    <!-- Online option -->
                    <div @click="selectedOption = 'link'"
                        :class="selectedOption === 'link' ? 'border-zinc-300 bg-zinc-50' : 'border-gray-200 bg-white hover:border-gray-300 hover:bg-gray-50'"
                        class="relative p-4 rounded-lg border cursor-pointer transition-all duration-200">
                        <!-- Radio button -->
                        <div class="absolute top-4 right-4">
                            <div :class="selectedOption === 'link' ? 'border-blue-500 bg-blue-500' : 'border-gray-300 bg-white'"
                                class="w-4 h-4 rounded-full border-2 flex items-center justify-center">
                                <div x-show="selectedOption === 'link'" class="w-2 h-2 rounded-full bg-white"></div>
                            </div>
                        </div>
                        <!-- Content -->
                        <div class="flex items-start mb-3">
                            <div :class="selectedOption === 'link' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600'"
                                class="p-4 rounded-md mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                    <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                                </svg>
                            </div>
                            <section>
                                <h3 :class="selectedOption === 'link' ? 'text-blue-900' : 'text-gray-900'"
                                    class="font-medium text-base">
                                    Online Meeting
                                </h3>
                                <p :class="selectedOption === 'link' ? 'text-blue-700' : 'text-gray-600'" class="text-sm">
                                    Video call via Google / Zoom
                                </p>
                            </section>
                        </div>
                    </div>
                </div>
                @endif

                <template x-if="selectedOption === 'location'">
                    <flux:textarea 
                        x-model="tempValues.location" 
                        label="Lokasi" 
                        placeholder="Masukkan lokasi disini..." 
                        rows="2" 
                        :disabled="$pastEndDate"
                    />
                </template>

                <template x-if="selectedOption === 'link'">
                    <div class="space-y-4">
                        <flux:textarea 
                            x-model="tempValues.link" 
                            label="Link" 
                            placeholder="Masukkan link disini..." 
                            rows="2" 
                            :disabled="$pastEndDate"
                        />
                        <flux:input 
                            x-model="tempValues.password" 
                            label="Password" 
                            :disabled="$pastEndDate"
                        />
                    </div>
                </template>

                <flux:input 
                    wire:model="topic" 
                    label="Topik" 
                    placeholder="Masukkan topik yang akan dibahas..." 
                    :disabled="$pastEndDate"
                />
                
                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="date" type="date" label="Tanggal" min="{{ now()->toDateString() }}" :disabled="$pastEndDate" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="startAt" type="time" label="Mulai" :disabled="$pastEndDate" />
                    <flux:input wire:model="endAt" type="time" label="Sampai" :disabled="$pastEndDate" />
                </div>
                @if (empty($result))
                <div class="flex justify-end">
                    <flux:button type="submit" variant="primary" @click="prepareForSubmit()">
                        Ubah
                    </flux:button>
                </div>
                @endif
            </div>

            <!-- Right Column - Full width result textarea -->
            <div class="space-y-3">
                @if (!empty($result))
                <flux:heading size="lg">Hasil Meeting</flux:heading>
                    <flux:textarea 
                        wire:model="result" 
                        placeholder="Masukkan hasil meeting disini..." 
                        rows="6"
                    />
                    <div class="space-y-2">
                        <flux:heading size="lg">Perlu kirim email perubahan?</flux:heading>
                        <flux:checkbox.group wire:model="recipients">
                            <div class="flex gap-4 *:gap-x-2">
                                <flux:checkbox value="kapusdatin" label="Kapusdatin" />
                                <flux:checkbox value="user" label="Pemohon" />
                            </div>
                        </flux:checkbox.group>
                    </div>
                    <div class="flex justify-end">
                        <flux:button type="submit" variant="primary" @click="prepareForSubmit()">
                            Ubah
                        </flux:button>
                    </div>
                @endif
                
            </div>
        </form>
    </x-layouts.requests.show>
</div>