<section>
    <flux:button :href="route('is.index')" icon="arrow-long-left" variant="subtle">Kembali ke Tabel</flux:button>

    <flux:heading size="lg" class="p-4">Daftar Versi Dokumen</flux:heading>

    {{-- Comparison Container --}}
    <x-layouts.requests.show :id="$systemRequestId" overViewRoute="is.show" activityRoute="is.activity">
        <div class="items-center mb-4 p-1">
            <flux:heading size="lg">Silahkan Pilih Versi dan Dokumen</flux:heading>
            <flux:subheading>Jika disamping nama dokumen ada tanda (*) maka versi tersebut adalah versi aktif
            </flux:subheading>
        </div>

        <x-documents.any-document-versions title="Versi lain" :anyVersions="$anyVersions" />
    </x-layouts.requests.show>
</section>