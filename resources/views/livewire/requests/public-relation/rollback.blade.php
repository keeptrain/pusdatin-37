<div>
    <flux:button :href="route('pr.show', [$prRequestId])" icon="arrow-long-left" variant="subtle" wire:navigate>
        Kembali
    </flux:button>

    {{-- Success is as dangerous as failure. --}}
    <form wire:submit="update">
        <div class="p-4 space-y-6">
            <flux:heading size="lg">Edit permohonan layanan</flux:heading>
            <div>
                @foreach ($this->availableMedia as $media)
                    <div class="border p-4 space-y-2 mb-6">
                        <flux:legend>Materi {{ $media['part_number_label'] }}</flux:legend>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2"> 
                                <flux:input type="file" wire:model="mediaFiles.{{ $media['part_number'] }}"
                                    label="File" />
                                <flux:text label="File SOP">
                                    File sebelumnya: {{ basename($media['file_path']) }}
                                </flux:text>
                            </div>
                            <div>
                                @if (isset($media['links']))
                                    <flux:textarea wire:model="links.{{ $media['part_number'] }}" label="Link materi"
                                        placeholder="Input link disini..." rows="2" />
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-end">
                <flux:button variant="ghost">Batal</flux:button>
                <flux:button type="submit" variant="primary">Update</flux:button>
            </div>
        </div>
    </form>
</div>
