<section>
    <flux:modal wire:model="showModal" name="confirm-letter-verification" focusable class="md:w-120" size="lg">
        <form wire:submit="save" class="space-y-6">
            <div>
                <flux:heading size="lg">
                    {{ __('Verifikasi Surat') }}
                </flux:heading>
                <flux:subheading>
                    dengan judul {{ $letterId }}
                </flux:subheading>
            </div>

            <flux:radio.group wire:model="status" label="Status" badge="Required">
                <flux:radio value="approved" label="Approved" x-on:click="$wire.openNotesClosePart()" />
                <flux:radio value="replied" label="Replied" x-on:click="$wire.openPartCloseNotes()" />
                <flux:radio value="rejected" label="Rejected" x-on:click="$wire.openPartCloseNotes()" />
                <flux:radio value="wrong" label="Test failed" x-on:click="$wire.showPart = false" />
            </flux:radio.group>

            <div x-data="{
                revisionPart: [],
            }" wire:show="showPart">
                <flux:checkbox.group wire:model="revisionParts" label="Part" class="space-y-4">
                    <flux:checkbox.all label="All part" />
                    <flux:checkbox value="part1" label="Part1" x-model="revisionPart" />

                    <template x-if="revisionPart.includes('part1')">
                        <flux:textarea wire:model.defer="revisionNotes.part1" cols="66" rows="2"
                            placeholder="Catatan untuk Part1" resize="vertical" />
                    </template>

                    <flux:checkbox value="part2" label="Part2" x-model="revisionPart" />

                    <template x-if="revisionPart.includes('part2')">
                        <flux:textarea wire:model.defer="revisionNotes.part2" cols="66" rows="2"
                            placeholder="Catatan untuk Part2" resize="vertical" />
                    </template>

                    <flux:checkbox value="part3" label="Part3" x-model="revisionPart" />

                    <template x-if="revisionPart.includes('part3')">
                        <flux:textarea wire:model.defer="revisionNotes.part3" cols="66" rows="2"
                            placeholder="Catatan untuk Part3" resize="vertical" />
                    </template>
                </flux:checkbox.group>

            </div>

            <div wire:show="showNotes" x-transition.duration.1000ms>
                <flux:textarea wire:model="notes" label="Notes" cols="66" rows="4"
                    placeholder="No lettuce, tomato, or onion..." resize="horizontal" />
            </div>
            <div class="flex justify-end space-x-2">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="primary" type="submit">{{ __('Verifikasi') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</section>
