<div>
    <flux:button :href="route('is.show', [$letterId])" 
        icon="arrow-long-left" variant="subtle">Kembali</flux:button>

    <div class="grid grid-cols-2">
        <x-documents.current-document title="Sebelum revisi" :mapping="$currentVersions" />

        <x-documents.current-document title="Sesudah revisi" :mapping="$latestRevisions" />
    </div>

    {{-- Comparison Details --}}
    <div x-data="{ changesChoice: '' }" class="bg-gray-50 border-t border-gray-200 p-4 m-4 space-y-4">
        <flux:legend>Catatan sebelumnnya</flux:legend>

        <div class="space-y-3">
            @foreach ($latestRevisions as $map)
                {{-- Modified Content --}}
                <div class="bg-white rounded-lg p-3 shadow-sm border border-yellow-100">
                    <div class="flex items-center space-x-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="text-sm text-gray-700">
                                <span class="font-semibold text-yellow-600">Untuk bagian {{ $map['part_number_label'] }}:
                                    {{ $map['revision_note'] }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <flux:legend>Terima perubahan</flux:legend>
        
        <flux:radio.group wire:model="changesChoice">
            <flux:radio value="yes" label="Ya" x-on:click="changesChoice ='ya'" />
            <flux:radio value="no" label="Tidak" x-on:click="changesChoice ='tidak'" />
        </flux:radio.group>

        @error('changesChoice')
            <flux:text variant="strong" class="text-red-500 flex items-center mt-2">
                <flux:icon.exclamation-circle />{{ $message }}
            </flux:text>
        @enderror

        <template x-if="changesChoice === 'ya'">
            <flux:fieldset>

                <flux:legend>Pilih perubahan sesudah revisi</flux:legend>

                <flux:checkbox.group wire:model="partAccepted">
                    <div class="flex gap-4 *:gap-x-2">
                        @foreach ($latestRevisions as $map)
                            <flux:checkbox :value="$map['part_number']" :label="$map['part_number_label']" />
                        @endforeach
                    </div>
                </flux:checkbox.group>

                @error('partAccepted')
                    <flux:text variant="strong" class="text-red-500 flex items-center mt-2">
                        <flux:icon.exclamation-circle />{{ $message }}
                    </flux:text>
                @enderror
            </flux:fieldset>
        </template>

        <flux:textarea wire:model="note" placeholder="Beri catatan kepada pemohon..." />

    </div>
    <div class="flex justify-end m-4">
        <flux:button wire:click="save" variant="primary">Konfirmasi</flux:button>
    </div>
    {{-- <section>
        <!-- Empty notificaiton -->
        <div class="flex flex-col items-center justify-center p-8">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-4">
                <flux:icon.magnifying-glass class="size-12" />
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No reviews</h3>
            <p class="text-center text-gray-500">You're all caught up! Check back later for new updates.</p>
        </div>

    </section> --}}
</div>