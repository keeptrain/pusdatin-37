<div class="space-y-4">
    <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-2">
                <p class="text-sm font-medium text-yellow-800 mb-2">
                    Aksi yang akan dilakukan jika mengubah status:
                </p>
                <ul class="text-sm font-light text-gray-600 list-disc pl-3">
                    <li>Status berserta kondisi akan dikembalikan ke tahap status yang dipilih</li>
                    <li>Menambahkan riwayat tracking terjadi rollback ke tahap status yang dipilih</li>
                    <li>Jika sedang berada di status revisi, maka permintaan revisi akan dihapus</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- <template x-if="status === 'pending'">
        <div class="space-y-2">
            <h4 class="font-medium text-gray-900">Mengembalikan status ke permohonan masuk</h4>
            <ul class="ml-5 space-y-1 text-sm text-gray-600 list-disc">
                <li>Menghapus riwayat tracking selain permohonan masuk</li>
                <li>Mengatur ulang status ke tahap awal</li>
            </ul>
        </div>
    </template>

    <template x-if="status === 'disposition'">
        <div class="space-y-2">
            <h4 class="font-medium text-gray-900">Mengembalikan status ke disposisi</h4>
            <ul class="ml-5 space-y-1 text-sm text-gray-600 list-disc">
                <li>Status akan dikembalikan ke tahap disposisi</li>
                <li>Menambahkan riwayat tracking terjadi rollback ke tahap disposisi</li>
            </ul>
        </div>
    </template>

    <template x-if="status === 'approved_kasatpel'">
        <div class="space-y-2">
            <h4 class="font-medium text-gray-900">Mengembalikan status ke disetujui Kasatpel</h4>
            <ul class="ml-5 space-y-1 text-sm text-gray-600 list-disc">
                <li>Status akan dikembalikan ke tahap persetujuan Kasatpel</li>
                <li>Menambahkan riwayat tracking terjadi rollback ke tahap disetujui Kasatpel</li>
            </ul>
        </div>
    </template>

    <template x-if="status === 'approved_kapusdatin'">
        <div class="space-y-2">
            <h4 class="font-medium text-gray-900">Mengembalikan status ke disetujui Kapusdatin</h4>
            <ul class="ml-5 space-y-1 text-sm text-gray-600 list-disc">
                <li>Status akan dikembalikan ke tahap persetujuan Kapusdatin</li>
                <li>Menambahkan riwayat tracking terjadi rollback ke tahap disetujui Kapusdatin</li>
            </ul>
        </div>
    </template>

    <template x-if="status === 'process'">
        <div class="space-y-2">
            <h4 class="font-medium text-gray-900">Mengembalikan status ke proses</h4>
            <ul class="ml-5 space-y-1 text-sm text-gray-600 list-disc">
                <li>Status akan dikembalikan ke tahap proses</li>
                <li>Menambahkan riwayat tracking terjadi rollback ke tahap proses</li>
            </ul>
        </div>
    </template>

    <template x-if="status === 'rejected'">
        <div class="space-y-2">
            <h4 class="font-medium text-gray-900">Mengembalikan status ke ditolak</h4>
            <ul class="ml-5 space-y-1 text-sm text-gray-600 list-disc">
                <li>Status menjadi ditolak</li>
                <li>Menambahkan riwayat tracking telah di tolak</li>
            </ul>
        </div>
    </template> --}}
</div>