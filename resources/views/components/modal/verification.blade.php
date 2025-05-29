<flux:modal x-data="{
    status: '',
}" name="verification-modal" focusable class="md:w-120" size="lg">

    <form wire:submit="save" class="space-y-6">
        <div>
            <flux:heading size="lg">
                {{ __('Verifikasi permohonan layanan') }}
            </flux:heading>
        </div>

        <flux:radio.group wire:model="status" name="status" label="Status" badge="Required" data-checked>
            <flux:radio value="approved_kasatpel" name="status" label="Disetujui Kasatpel"
                x-on:click="status = 'approved'" />
            <flux:radio value="replied" name="status" label="Revisi" x-on:click="status = 'replied'" />
            <flux:radio value="rejected" name="status" label="Ditolak Kasatpel" x-on:click="status = 'rejected'" />
            {{--
            <flux:radio value="wrong" name="status" label="Test failed" x-on:click="status = 'wrong'" /> --}}
        </flux:radio.group>

        <template x-if="status === 'approved'">
            <flux:textarea wire:model="notes" label="Notes" cols="66" rows="4" placeholder="Catatan (opsional)..."
                resize="horizontal" />
        </template>

        <template x-if="status === 'replied'">
            <div x-data="{
                revisionPart: [],
            }">
                <flux:checkbox.group wire:model="revisionParts" label="Bagian" class="space-y-4">
                    @foreach ($availablePart as $part)
                        <flux:checkbox :value="$part['part_number']" :label="$part['part_number_label']"
                            x-model="revisionPart" />
                    @endforeach

                    <x-modal.partials.revision-notes />

                    <flux:checkbox value="otherPart" label="Other" x-model="revisionPart" />

                    <template x-if="revisionPart.includes('otherPart')">
                        <flux:textarea wire:model.defer="revisionNotes.otherPart" cols="66" rows="2"
                            placeholder="Catatan untuk bagian lain" resize="vertical" />
                    </template>
                </flux:checkbox.group>
            </div>
        </template>

        <div class="flex justify-end space-x-2">
            <flux:modal.close>
                <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
            </flux:modal.close>

            <flux:button variant="primary" type="submit">{{ __('Verifikasi') }}</flux:button>
        </div>
    </form>
</flux:modal>