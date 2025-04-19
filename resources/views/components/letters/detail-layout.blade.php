@props([
    'letterId' => null,
])

@php
    $classes = Flux::classes();
    $currentRoute = request()->route()->getName();
    $currentParams = request()->route()->parameters();

@endphp

<div class="mt-6">
    <flux:heading size="xl">Application Request Service</flux:heading>
    <div class="flex mt-4 gap-6 lg:flex-row ">
        {{-- Kiri: 70% - Tabs & Content --}}
        <div class="w-full lg:w-[70%]">
            {{-- Tabs Navigation --}}
            <div class="border-b border-gray-300 mb-4">
                <flux:navbar class="overflow-y-auto whitespace-nowrap">
                    <flux:navbar.item 
                        :href="route('letter.detail', $letterId)" 
                        :current="$currentRoute === 'letter.detail' && $currentParams['id'] == $letterId"
                        icon="eye">
                        Overview
                    </flux:navbar.item>

                    <flux:navbar.item 
                        :href="route('letter.edit', $letterId)"
                        :current="$currentRoute === 'letter.edit' && $currentParams['id'] == $letterId"
                        icon="pencil">
                        Edit
                    </flux:navbar.item>

                    <flux:navbar.item 
                        :href="route('letter.activity', $letterId)"
                        :current="$currentRoute === 'letter.activity' && $currentParams['id'] == $letterId"
                        icon="arrow-path">
                        Activity
                    </flux:navbar.item>

                    <flux:navbar.item 
                        :href="route('letter.chat', $letterId)"
                        :current="$currentRoute === 'letter.chat' && $currentParams['id'] == $letterId"
                        icon="envelope">
                        Chat
                    </flux:navbar.item>
                </flux:navbar>
            </div>

            {{-- Tabs Content --}}
            <div class="mt-4">
                {{ $slot }}
            </div>
        </div>


        @if (isset($rightPanel))
            {{-- Kanan: 30% - Panel Detail --}}
        <div class="hidden lg:block w-[30%] border-l border-gray-200 pl-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">Detail Informasi</h3>

            <ul class="space-y-2 text-sm text-gray-600">
                <li>
                    {{ $rightPanel }}                    
                
                </li>
                {{-- Tambahkan detail lain jika perlu --}}
            </ul>
        </div>
        @endif
        
    </div>
</div>
