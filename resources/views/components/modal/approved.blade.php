<flux:modal x-data="{
    status: '',
}" name="approved-modal" focusable class="md:w-120" size="lg">
    <form wire:submit="save" class="space-y-6">
        <flux:heading size="lg">
            {{ __('Persetujuan permohonan layanan') }} {{ $letterId }}
        </flux:heading>

        <!-- Radio Group -->
        <flux:radio.group wire:model="status" name="status" label="Status" badge="Required">
            <flux:radio value="approved_kapusdatin" name="status" label="Disetujui Kapusdatin"
                x-on:click="status = 'approved'" />
            <flux:radio value="replied_kapusdatin" name="status" label="Revisi" x-on:click="status = 'replied'" />
            {{--
            <flux:radio value="wrong" name="status" label="Test failed" x-on:click="status = 'wrong'" /> --}}
        </flux:radio.group>

        <template x-if="status === 'approved_kapusdatin'">
            <flux:textarea wire:model="notes" cols="66" rows="2" placeholder="Catatan tambahan..." resize="vertical" />
        </template>

        <!-- Template untuk Replied -->
        <template x-if="status === 'replied'">
            <div x-data="{
                    revisionPart: [],
                }">
                <flux:checkbox.group wire:model="revisionParts" label="Part" class="space-y-4">
                    @foreach ($availablePart as $part)
                        @php
                            $label = match ($part) {
                                1 => 'Nota dinas',
                                2 => 'Sop',
                                3 => 'Pendukung',
                            };
                        @endphp
                        <flux:checkbox :value="$part" label="{{ $label }}" x-model="revisionPart" />
                    @endforeach
                    <template x-if="revisionPart.includes('1')">
                        <flux:textarea wire:model.defer="revisionNotes.1" cols="66" rows="2"
                            placeholder="Catatan untuk nota dinas" resize="vertical" />
                    </template>

                    <template x-if="revisionPart.includes('2')">
                        <flux:textarea wire:model.defer="revisionNotes.2" cols="66" rows="2"
                            placeholder="Catatan untuk sop" resize="vertical" />
                    </template>

                    <template x-if="revisionPart.includes('3')">
                        <flux:textarea wire:model.defer="revisionNotes.3" cols="66" rows="2"
                            placeholder="Catatan untuk pendukung" resize="vertical" />
                    </template>

                    <flux:checkbox value="otherPart" label="Other" x-model="revisionPart" />

                    <template x-if="revisionPart.includes('otherPart')">
                        <flux:textarea wire:model.defer="revisionNotes.otherPart" cols="66" rows="2"
                            placeholder="Catatan untuk bagian lain" resize="vertical" />
                    </template>
                </flux:checkbox.group>
            </div>
        </template>

        {{-- <!-- Template untuk Test Failed -->
        <template x-if="status === 'wrong'">
            <p class="text-gray-700">Test gagal. Silakan periksa kembali.</p>
        </template> --}}

        <div class="flex justify-end space-x-2">
            <flux:modal.close>
                <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
            </flux:modal.close>

            <flux:button variant="primary" type="submit">{{ __('Verifikasi') }}</flux:button>
        </div>
    </form>
</flux:modal>