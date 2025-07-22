<div x-data="{ status: '' }">
    <flux:button :href="route('is.show', [$systemRequestId])" icon="arrow-long-left" variant="subtle">
        Batal
    </flux:button>

    <form x-data="rollbackTable" class="p-4">
        <div class="space-y-4">
            <flux:heading size="lg">Rollback</flux:heading>
            <div class="space-y-6">
                @if (!$systemRequest->status instanceof Pending)
                    <div class="flex items-center">
                        <div class="flex flex-row gap-4">
                            <flux:text>Ubah status dari: </flux:text>
                            <flux:notification.status-badge :status="$this->status" />
                        </div>
                        <flux:icon.arrow-long-right class="size-6 pt-1 ml-6 mr-6" />
                        <div>
                            <flux:select wire:model.live="changeStatus" size="sm" placeholder="Ke status..."
                                x-model="status">
                                <flux:select.option value="pending">Permohonan Masuk</flux:select.option>
                                <flux:select.option value="disposition">Disposisi</flux:select.option>
                                <flux:select.option value="approved_kasatpel">Disetujui Kasatpel</flux:select.option>
                                <flux:select.option value="approved_kapusdatin">Disetujui Kapusdatin</flux:select.option>
                                <flux:select.option value="process">Proses</flux:select.option>
                                <flux:select.option value="rejected">Ditolak</flux:select.option>
                            </flux:select>
                        </div>
                    </div>
                @endif
                <div>
                    <flux:radio.group x-model="currentDivision" name="status" label="Pindahkan kasatpel"
                        :disabled="empty($currentDivision)">
                        <flux:radio value="si" label="Sistem Informasi" />
                        <flux:radio value="data" label="Pengelolaan Data" />
                    </flux:radio.group>
                </div>
            </div>
            <x-letters.information-rollback />
        </div>

        <div class="mt-12 space-y-4">
            <div class="flex justify-between">
                <div>
                    <flux:heading size="lg">Atau jika anda ingin menghapus pesan tracking</flux:heading>
                    <flux:text>* jika sebelumnya sudah terhapus, lalu mencentang checkbox maka pesan akan kembali
                        tampil.</flux:text>
                </div>

                <flux:button x-on:click="$dispatch('modal-show', {name: 'rollback-track'});" size="sm" icon="eye">
                    Filter
                </flux:button>
            </div>
            <flux:table.base :perPage="$perPage" :paginate="$trackingHistorie">
                <x-slot name="header">
                    <flux:table.column>
                    </flux:table.column>
                    <flux:table.column>Pesan</flux:table.column>
                    <flux:table.column class="w-60">Tanggal</flux:table.column>
                </x-slot>
                <x-slot name="body">
                    @foreach ($trackingHistorie as $historie)
                        <tr wire:key="{{ $historie->id }}" class="hover:bg-zinc-100 dark:hover:bg-zinc-700">
                            <flux:table.row class="py-3">
                                <flux:checkbox x-model="trackId" value="{{ $historie->id }}" />
                            </flux:table.row>
                            <flux:table.row>{{ $historie->message }} </flux:table.row>
                            <flux:table.row>{{ $historie->created_at }}</flux:table.row>
                        </tr>
                    @endforeach
                </x-slot>
            </flux:table.base>
        </div>

        <div class="flex justify-end">
            <flux:button x-on:click="save()" variant="primary" icon="backward">
                Rollback
            </flux:button>
        </div>

        <flux:modal name="rollback-track" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Filter tabel riwayat tracking</flux:heading>
                </div>

                <flux:radio.group x-model="selectedDeleteRecords" label="Tampilkan pesan yang dihapus">
                    <flux:radio label="Tidak termasuk yang dihapus" value="all" />
                    <flux:radio label="Termasuk yang dihapus" value="withDeleted" />
                    <flux:radio label="Hanya yang dihapus" value="onlyDeleted" />
                </flux:radio.group>

                {{-- <flux:checkbox.group label="Pesan dibuat pada bagian">
                    <flux:checkbox label="Kapusdatin" value="oldest" />
                    <flux:checkbox label="Kasatpel" value="oldest" />
                    <flux:checkbox label="Pemohon" value="latest" />
                </flux:checkbox.group> --}}

                <div class="flex justify-end">
                    {{-- <flux:modal.close>
                        <flux:button variant="subtle">Reset</flux:button>
                    </flux:modal.close> --}}

                    <flux:button x-on:click="setFilter()" type="button" variant="primary">Terapkan</flux:button>
                </div>
            </div>
        </flux:modal>
    </form>

    @script
    <script>
        Alpine.data('rollbackTable', () => ({
            trackId: [],
            currentDivision: '',
            selectedDeleteRecords: '',

            init() {
                this.$nextTick(() => {
                    this.currentDivision = $wire.currentDivision;
                    this.selectedDeleteRecords = $wire.deletedRecords;
                });
            },

            setFilter() {
                $wire.set('deletedRecords', this.selectedDeleteRecords).then(() => {
                    this.$dispatch('modal-close', { name: 'rollback-track' });
                });
            },

            save() {
                $wire.set('trackId', this.trackId);
                $wire.set('currentDivision', this.currentDivision);
                $wire.save();
            }
        }));
    </script>
    @endscript
</div>