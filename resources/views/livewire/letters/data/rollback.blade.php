<div>
    <flux:button :href="route('letter.detail', [$letterId])" icon="arrow-long-left" variant="subtle">Cancel
    </flux:button>

    <form wire:submit="save">
        <div class="p-4 space-y-6">
            <flux:heading size="xl">Rollback Request Service</flux:heading>

            <div class="space-y-2">
                <div class="flex">
                    <flux:text> Change status </flux:text>
                </div>

                <div class="flex">
                    <flux:notification.status-badge
                        status="{{ $this->letter->status->label() }}">{{ $this->letter->status->label() }}</flux:notification.status-badge>
                    <flux:icon.arrow-long-right class="size-6 pt-1 ml-6 mr-6" - />
                    <div>
                        <flux:select wire:model="changeStatus" size="sm" placeholder="Choose status...">
                            <flux:select.option value="pending">Pending</flux:select.option>
                            <flux:select.option value="process">Process</flux:select.option>
                            <flux:select.option value="replied">Replied</flux:select.option>
                            <flux:select.option value="approved">Approved</flux:select.option>
                            <flux:select.option value="rejected">Rejected</flux:select.option>
                        </flux:select>
                    </div>
                </div>
            </div>

            {{-- <flux:radio.group wire:model="status" name="status" label="Status" badge="Required" data-checked>
                <flux:radio value="approved" name="status" label="Approved" x-on:click="$wire.openNotesClosePart()" />
                <flux:radio value="replied" name="status" label="Replied" x-on:click="$wire.openPartCloseNotes()" />
                <flux:radio value="rejected" name="status" label="Rejected" x-on:click="$wire.openNotesClosePart()" />
                <flux:radio value="wrong" name="status" label="Test failed" x-on:click="$wire.showPart = false" />
            </flux:radio.group> --}}

            <div class="border border-gray-200 rounded-lg mt-4 md:mt-8">
                <div class="flex flex-1 justify-between items-center bg-zinc-50 border-b">
                    <flux:text class="ml-3">Remove request tracks</flux:text>
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
                                    <flux:menu.radio value="withoutDeleted">Without deleted records</flux:menu.radio>
                                    <flux:menu.radio value="withDeleted">With deleted records</flux:menu.radio>
                                    <flux:menu.radio value="onlyDeleted">Only deleted records</flux:menu.radio>
                                </flux:menu.radio.group>
                            </flux:menu.submenu>

                            {{--
                            <flux:menu.separator /> --}}

                            {{-- <flux:menu.item variant="danger">Delete</flux:menu.item> --}}
                        </flux:menu>
                    </flux:dropdown>
                </div>

                <flux:checkbox.group wire:model.live="trackId" class="h-60 p-3 overflow-y-auto">
                    @foreach ($this->letter->requestStatusTrack as $track)
                        <div class="mb-4">
                            <flux:checkbox wire:key="{{ $track->id }}" value="{{ $track->id }}"
                                label="{{ $track->created_at->format('d F y, H:i') }}" description="{{ $track->action }}" />
                        </div>
                    @endforeach
                </flux:checkbox.group>

            </div>

            <flux:modal.trigger name="rollback-track">
                <flux:button type="button" variant="primary">Rollback</flux:button>
            </flux:modal.trigger>

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