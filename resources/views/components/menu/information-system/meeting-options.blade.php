<!-- In Person Option -->
<div @click="selectedOption === 'in-person' ? selectedOption = '' : selectedOption = 'in-person'"
    x-modelable="selectedOption" {{ $attributes }} :class="selectedOption === 'in-person' ? 'border-zinc-300 bg-zinc-50' :
        'border-gray-200 bg-white hover:border-gray-300 hover:bg-gray-50'"
    class="relative p-4 rounded-lg border transition-all duration-200">
    <!-- Radio button -->
    <div class="absolute top-4 right-4">
        <div :class="selectedOption === 'in-person' ? 'border-blue-500 bg-blue-500' : 'border-gray-300 bg-white'"
            class="w-4 h-4 rounded-full border-2 flex items-center justify-center">
            <div x-show="selectedOption === 'in-person'" class="w-2 h-2 rounded-full bg-white"></div>
        </div>
    </div>

    <!-- Content -->
    <div class="flex items-start mb-3">
        <div :class="selectedOption === 'in-person' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600'"
            class="p-4 rounded-md mr-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                <circle cx="12" cy="10" r="3"></circle>
            </svg>
        </div>
        <section>
            <h3 :class="selectedOption === 'in-person' ? 'text-blue-900' : 'text-gray-900'" class="font-medium text-base">
                Pertemuan Langsung
            </h3>
            <p :class="selectedOption === 'in-person' ? 'text-blue-700' : 'text-gray-600'" class="text-sm">
                Bertemu secara langsung di lokasi
            </p>
        </section>
    </div>

    <section @click.stop x-show="selectedOption === 'in-person'" class="space-y-4">
        <flux:input wire:model="meeting.topic" label="Topik" placeholder="Masukkan topik yang akan dibahas..." />
        <flux:textarea wire:model="meeting.location" label="Lokasi" placeholder="Masukkan lokasi disini..." rows="1" />
        <div>
            <flux:input wire:model="meeting.date" type="date" label="Tanggal" min="{{ now()->toDateString() }}" />
        </div>
        <div class="grid grid-cols-2 gap-4">
            <flux:input wire:model="meeting.start" type="time" label="Mulai" />
            <flux:input wire:model="meeting.end" type="time" label="Sampai" />
        </div>
        <div class="space-y-2">
            <flux:heading size="lg">Kirim email ke</flux:heading>
            <flux:checkbox.group wire:model="meeting.recipients">
                <div class="flex gap-4 *:gap-x-2">
                    <flux:checkbox value="kapusdatin" label="Kapusdatin" />
                    <flux:checkbox value="kasatpel" label="Kasatpel" />
                    <flux:checkbox value="user" label="Pemohon" checked />
                </div>
            </flux:checkbox.group>
            @error('meeting.recipients')
                <flux:text variant="strong" class="text-red-500 flex font-bold items-center">
                    <flux:icon.exclamation-triangle />
                    {{ $message }}
                </flux:text>
            @enderror
        </div>
        <div class="mt-6 flex justify-end">
            <flux:button type="submit" variant="primary">
                Buat
            </flux:button>
        </div>
    </section>
</div>

<!-- Online Meet Option -->
<div @click="selectedOption === 'online-meet' ? selectedOption = '' : selectedOption = 'online-meet'"
    x-modelable="selectedOption" {{ $attributes }} :class="selectedOption === 'online-meet' ? 'border-zinc-300 bg-zinc-50' :
        'border-gray-200 bg-white hover:border-gray-300 hover:bg-gray-50'"
    class="relative p-4 rounded-lg border cursor-pointer transition-all duration-200">
    <!-- Radio button -->
    <div class="absolute top-4 right-4">
        <div :class="selectedOption === 'online-meet' ? 'border-blue-500 bg-blue-500' : 'border-gray-300 bg-white'"
            class="w-4 h-4 rounded-full border-2 flex items-center justify-center">
            <div x-show="selectedOption === 'online-meet'" class="w-2 h-2 rounded-full bg-white"></div>
        </div>
    </div>

    <!-- Content -->
    <div class="flex items-start mb-3">
        <div :class="selectedOption === 'online-meet' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600'"
            class="p-4 rounded-md mr-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="23 7 16 12 23 17 23 7"></polygon>
                <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
            </svg>
        </div>
        <section>
            <h3 :class="selectedOption === 'online-meet' ? 'text-blue-900' : 'text-gray-900'"
                class="font-medium text-base">
                Online Meeting
            </h3>
            <p :class="selectedOption === 'online-meet' ? 'text-blue-700' : 'text-gray-600'" class="text-sm">
                Video call via Google / Zoom
            </p>
        </section>
    </div>

    <section @click.stop x-show="selectedOption === 'online-meet'" class="space-y-4">
        <flux:input wire:model="meeting.topic" label="Topik" placeholder="Masukkan topik yang akan dibahas..." />
        <div class="grid grid-cols-2 items-start gap-4">
            <flux:textarea wire:model="meeting.link" label="Link" placeholder="Masukkan link disini..." rows="2"
                class="w-full" />
            <flux:input wire:model="meeting.password" label="Password" class="w-1/2" />
        </div>
        <div>
            <flux:input wire:model="meeting.date" type="date" label="Tanggal" min="{{ now()->toDateString() }}" />
        </div>
        <div class="grid grid-cols-2 gap-4">
            <flux:input wire:model="meeting.start" type="time" label="Mulai" />
            <flux:input wire:model="meeting.end" type="time" label="Sampai" />
        </div>
        <div class="space-y-2">
            <flux:heading size="lg">Kirim email ke</flux:heading>
            <flux:checkbox.group wire:model="meeting.recipients">
                <div class="flex gap-4 *:gap-x-2">
                    <flux:checkbox value="kapusdatin" label="Kapusdatin" />
                    <flux:checkbox value="kasatpel" label="Kasatpel" />
                    <flux:checkbox value="user" label="Pemohon" checked />
                </div>
            </flux:checkbox.group>
        </div>
        <div class="flex justify-end">
            <flux:button type="submit" variant="primary">
                Buat
            </flux:button>
        </div>
    </section>
</div>