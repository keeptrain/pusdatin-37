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
                        status="{{ $letter->status->label() }}">{{ $letter->status->label() }}</flux:notification.status-badge>
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

            <flux:checkbox.group wire:model="trackId" label="Remove request status tracks" class="h-60 overflow-y-auto">
                @foreach ($letter->requestStatusTrack as $track)
                    <flux:checkbox value="{{ $track->id }}" label="{{ $track->created_at }}"
                        description="{{ $track->action }}" />
                @endforeach
            </flux:checkbox.group>

            <flux:modal.trigger name="rollback-track">
                <flux:button type="button" variant="primary">Rollback</flux:button>
            </flux:modal.trigger>

            <flux:modal name="rollback-track" class="min-w-[22rem]">
                <div class="space-y-6">
                    <div>
                        <flux:heading size="lg">Rollback?</flux:heading>

                        <flux:text class="mt-2">
                            <p>You're about to rollback this status track.</p>
                            <p>This action will remove the track status for users and cannot be undone.</p>
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