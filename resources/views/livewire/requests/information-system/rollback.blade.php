<div x-data="{ status: '' }">
    <flux:button :href="route('is.show', [$systemRequestId])" icon="arrow-long-left" variant="subtle">Batal
    </flux:button>

    <form wire:submit="save">
        <div class="p-4 space-y-6">
            <flux:heading size="lg">Rollback</flux:heading>

            <div class="space-y-2">
                <div class="flex">
                    <flux:text>Ubah status</flux:text>
                </div>

                <div class="flex">
                    <flux:notification.status-badge :status="$this->systemRequest->status" />
                    <flux:icon.arrow-long-right class="size-6 pt-1 ml-6 mr-6" />
                    <div>
                        <flux:select wire:model="changeStatus" size="sm" placeholder="Ke status..." x-model="status">
                            <flux:select.option value="pending">Permohonan Masuk</flux:select.option>
                            <flux:select.option value="disposition">Disposisi</flux:select.option>
                            <flux:select.option value="process">Proses</flux:select.option>
                            <flux:select.option value="replied">Balasan Kasatpel</flux:select.option>
                            <flux:select.option value="approved_kasatpel">Disetujui Kasatpel</flux:select.option>
                            <flux:select.option value="replied_kapusdatin">Balasan Kapusdatin</flux:select.option>
                            <flux:select.option value="approved_kapusdatin">Disetujui Kapusdatin</flux:select.option>
                            <flux:select.option value="rejected">Ditolak</flux:select.option>
                        </flux:select>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 rounded-lg p-4">
            <div class="border rounded-lg">
                <div class="bg-zinc-50 p-4">
                    <flux:text x-text="`Apa yang terjadi ketika mengubah status ke ${status}`" />

                </div>
                <div class="p-4">
                    <x-letters.information-rollback />
                </div>

            </div>

            {{-- <div class="border rounded-lg">
                <div class="flex flex-1 items-center justify-between p-2 bg-zinc-50">
                    <flux:text>Menghapus pesan tracking permohonan layanan </flux:text>
                    <flux:dropdown>
                        <flux:button variant="ghost">Filters
                            <flux:icon.funnel class="size-4.5" />
                        </flux:button>

                        <flux:menu>
                            <flux:menu.submenu heading="Sort by">
                                <flux:menu.radio.group wire:model.live="filter.sortBy">
                                    <flux:menu.radio value="latest">Latest</flux:menu.radio>
                                    <flux:menu.radio value="oldest">Oldest</flux:menu.radio>
                                </flux:menu.radio.group>
                            </flux:menu.submenu>

                            <flux:menu.submenu heading="Delete records">
                                <flux:menu.radio.group wire:model.live="filter.deletedRecords">
                                    <flux:menu.radio value="withoutDeleted">Without deleted records
                                    </flux:menu.radio>
                                    <flux:menu.radio value="withDeleted">With deleted records</flux:menu.radio>
                                    <flux:menu.radio value="onlyDeleted">Only deleted records</flux:menu.radio>
                                </flux:menu.radio.group>
                            </flux:menu.submenu>
                        </flux:menu>
                    </flux:dropdown>
                </div>

                <flux:checkbox.group wire:model.live="trackId" class="h-60 p-3 overflow-y-auto">
                    @foreach ($this->systemRequest->requestStatusTrack as $track)
                    <div class="mb-4">
                        <flux:checkbox wire:key="{{ $track->id }}" value="{{ $track->id }}"
                            label="{{ $track->created_at->format('d F y, H:i') }}" description="{{ $track->action }}" />
                    </div>
                    @endforeach
                </flux:checkbox.group>
            </div> --}}
        </div>

        <div class="flex justify-end">
            <flux:modal.trigger name="rollback-track">
                <flux:button type="button" variant="primary">Rollback</flux:button>
            </flux:modal.trigger>
        </div>

        <flux:modal name="rollback-track" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Rollback?</flux:heading>

                    <flux:text class="space-y-3">
                        <p>You're about to rollback this status track.</p>
                        <li>This action will remove the track status for users</li>
                        <li>If a previously existing track is selected, it will disappear</li>
                        <li>Likewise, if there is none, it will return.</li>
                    </flux:text>

                </div>

                <div class="flex gap-2">
                    <flux:spacer />

                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>

                    <flux:button type="submit" variant="primary">Confirm</flux:button>
                </div>
            </div>
        </flux:modal>
</div>
</form>
</div>