<section>
    <flux:button :href="route('letter.table')" icon="arrow-long-left" variant="subtle">Kembali ke Tabel</flux:button>

    <flux:heading size="xl" class="p-4">Perbandingan Versi</flux:heading>

    <x-letters.detail-layout overViewRoute="letter.detail" activityRoute="letter.activity" :id="$siDataRequestId">
        {{-- Comparison Container --}}
        @if ($this->checkAvailableAnyVersions())
            <div class="grid lg:grid-cols-2 gap-0 divide-x divide-gray-200">
                <x-documents.current-document title="Versi saat ini" :mapping="$this->currentVersion()" />
                <x-documents.any-document-versions title="Versi lain" :anyVersions="$this->anyVersions()" />
            </div>
        @else
            <!-- Empty version -->
            <div class="flex flex-col items-center p-8">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-4">
                    <flux:icon.x-mark />
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada versi lain</h3>
                <p class="text-center text-gray-500">di Permohonan layanan ini</p>
            </div>
        @endif
    </x-letters.detail-layout>
</section>