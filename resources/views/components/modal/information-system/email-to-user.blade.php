<flux:modal name="email-modal">
    <form wire:submit="sendMail" class="md:w-120 space-y-4">
        <div>
            <flux:legend>Kirim email</flux:legend>
            <flux:text>Kepada pemohon {{ $systemRequest->user->name }}</flux:text>
        </div>

        <flux:checkbox.group wire:model="emailChecked" label="Opsi">
            @if ($checkNeedNdaDocument)
            <flux:checkbox value="need-nda" label="Permintaan Surat Perjanjian Kerasahasiaan "
                description="Meminta kepada pemohon untuk segera mengirimkan surat perjanjian kerasahasiaa (NDA)." />
            @endif
            @if ($systemRequest->active_revision)
                <flux:checkbox value="reminder-revision" label="Segera memperbaiki dokumen yang tidak sesuai"
                    description="Meminta kepada pemohon untuk segera memperbaiki dokumen yang tidak sesuai ketentuan." />
            @endif
        </flux:checkbox.group>

        <div class="flex justify-end">
            <flux:button variant="primary" type="submit">
                Kirim
            </flux:button>
        </div>
    </form>
</flux:modal>