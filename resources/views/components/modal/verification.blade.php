<flux:modal name="verification-modal" focusable class="md:w-120" size="lg">
    <form x-data="verification" wire:submit="actionVerification" class="space-y-6">
        <div>
            <flux:heading size="lg">
                {{ __('Verifikasi permohonan layanan') }}
            </flux:heading>
        </div>

        <flux:radio.group wire:model="form.status" name="status" label="Status" badge="Diperlukan" data-checked>
            @php
                $label = auth()->user()->hasRole('head_verifier') ? 'Disetujui Kapusdatin' : 'Disetujui Kasatpel';
                $valueApproved = auth()->user()->hasRole('head_verifier') ? 'approved_kapusdatin' : 'approved_kasatpel';
                $valueReplied = auth()->user()->hasRole('head_verifier') ? 'replied_kapusdatin' : 'replied';
            @endphp
            <flux:radio :value="$valueApproved" name="status" :label="$label" x-on:click="status = 'approved'" />
            <flux:radio :value="$valueReplied" name="status" label="Revisi" x-on:click="status = 'replied'" />
            {{--
            <flux:radio value="rejected" name="status" label="Ditolak Kasatpel" x-on:click="status = 'rejected'" /> --}}
            {{--
            <flux:radio value="wrong" name="status" label="Test failed" x-on:click="status = 'wrong'" /> --}}
        </flux:radio.group>

        {{-- <template x-if="status === 'rejected'">
            <flux:textarea wire:model="form.notes" label="Notes" cols="66" rows="4" placeholder="Catatan (opsional)..."
                resize="horizontal" />
        </template> --}}

        <template x-if="status === 'replied'">
            <div>
                <flux:checkbox.group wire:model="form.revisionParts" label="Untuk bagian" class="space-y-4">
                    @foreach ($allowedParts as $part)
                        <flux:checkbox :value="$part['part_number']" :label="$part['part_number_label']"
                            x-model="revisionPart" />
                    @endforeach

                    <x-modal.partials.revision-notes />

                    {{--
                    <flux:checkbox value="otherPart" label="Other" x-model="revisionPart" /> --}}

                    {{-- <template x-if="revisionPart.includes('otherPart')">
                        <div>
                            @foreach ($allowedParts as $part)
                            <flux:textarea wire:model="form.revisionNotes.{{ $part['part_number'] }}" cols="6" rows="2"
                                placeholder="Catatan untuk bagian {{ $part['part_number_label'] }}" resize="vertical"
                                required />
                            @error('form.revisionNotes.' . $part['part_number'])
                            <flux:text variant="strong" color="red">
                                {{ $message }}
                            </flux:text>
                            @enderror
                            @endforeach
                        </div>
                    </template> --}}
                </flux:checkbox.group>
            </div>
        </template>

        <div class="flex justify-end space-x-2">
            <flux:modal.close>
                <flux:button variant="subtle">{{ __('Cancel') }}</flux:button>
            </flux:modal.close>

            <flux:button variant="primary" type="submit">{{ __('Verifikasi') }}</flux:button>
        </div>
    </form>
</flux:modal>
@script
<script>
    Alpine.data('verification', () => ({
        status: '',
        revisionPart: [],
    }));
</script>
@endscript