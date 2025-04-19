    <flux:modal wire:model="showModal" name="confirm-letter-verification" focusable class="max-w-lg">
        <form wire:submit="save" class="space-y-6">
            <div>
                <flux:heading size="lg">
                    {{ __('Verifikasi Surat') }}
                </flux:heading>
                <flux:subheading>
                    dengan judul {{ $letterId }}
                </flux:subheading>
            </div>

            <flux:radio.group wire:model="status" label="Status">
                <flux:radio value="approved" label="Approved" />
                <flux:radio value="replied" label="Replied" />
                <flux:radio value="rejected" label="Rejected" />
                <flux:radio value="wrong" label="Test failed" />
            </flux:radio.group>
         

            <flux:textarea label="Order notes" cols="66" rows="4"
                placeholder="No lettuce, tomato, or onion..." resize="horizontal" />

            <div class="flex justify-end space-x-2">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="primary" type="submit">{{ __('Verifikasi') }}</flux:button>
            </div>
        </form>
    </flux:modal>
