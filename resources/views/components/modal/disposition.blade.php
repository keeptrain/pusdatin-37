<flux:modal x-data="{
        status: '',
    }" name="disposition-modal" focusable class="md:w-120" size="lg">
    <form wire:submit="saveDisposition" class="space-y-6">
        <flux:heading size="lg">
            {{ __('Disposisi permohonan layanan') }} {{ $letterId }}
        </flux:heading>

        <!-- Radio Group -->
        <flux:radio.group wire:model="status" name="status" label="Status" badge="Required">
            <flux:radio value="disposition" name="status" label="Disposisi" x-on:click="status = 'approved'" />
            <flux:radio value="rejected" name="status" label="Rejected" x-on:click="status = 'rejected'" />
            <flux:radio value="wrong" name="status" label="Test failed" x-on:click="status = 'wrong'" />
        </flux:radio.group>

        <template x-if="status === 'approved'">
            <flux:fieldset class="space-y-2">
                <flux:legend>Divisi</flux:legend>

                <flux:description>Pilih divisi selanjutnya yang akan memproses</flux:description>

                <flux:radio.group wire:model="selectedDivision" name="selectedDivision" invalid>
                    <div class="flex gap-4 *:gap-x-2">
                        <flux:radio value="si" label="Sistem Informasi" />
                        <flux:radio value="data" label="Data" />
                    </div>
                </flux:radio.group>

                @error('selectedDivision')
                    <flux:text variant="strong" class="text-red-500 flex items-center"><flux:icon.exclamation-circle/>{{ $message }}</flux:text>
                @enderror
            </flux:fieldset>
        </template>

        {{-- <template x-if="status === 'approved'">
            <flux:textarea wire:model="notes" cols="66" rows="2" placeholder="Catatan tambahan..." resize="vertical" />
        </template> --}}

        <!-- Template untuk Replied -->
        <template x-if="status === 'replied'">
            <flux:badge>Badge untuk Replied</flux:badge>
        </template>

        <!-- Template untuk Rejected -->
        <template x-if="status === 'rejected'">
            <flux:textarea wire:model="notes" cols="66" rows="2" placeholder="Catatan penolakan (opsional) " resize="vertical" />
        </template>

        <!-- Template untuk Test Failed -->
        <template x-if="status === 'wrong'">
            <p class="text-gray-700">Test gagal. Silakan periksa kembali.</p>
        </template>

        <div class="flex justify-end space-x-2">
            <flux:modal.close>
                <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
            </flux:modal.close>

            <flux:button variant="primary" type="submit">{{ __('Verifikasi') }}</flux:button>
        </div>
    </form>
</flux:modal>