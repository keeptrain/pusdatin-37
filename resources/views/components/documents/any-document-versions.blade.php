<div x-data="anyDocumentVersions" class="p-1">
    <div class="flex flex-col mb-4 gap-4">
        <!-- Select Version -->
        <div class="flex w-1/2 gap-2">
            <flux:select x-model.number="selectedVersion" placeholder="Pilih versi" class="!w-1/2">
                <template x-for="version in versions" :key="version . version">
                    <option :value="version . version" x-text="'Versi ' + version.version"></option>
                </template>
            </flux:select>

            <!-- Select Document -->
            <flux:select x-model.number="selectedPart" placeholder="Pilih dokumen">
                <template x-for="part in availableParts" :key="part . part_number">
                    <option :value="part . part_number" x-text="part.part_number_label"></option>
                </template>
            </flux:select>

            {{-- <flux:button icon="ellipsis-vertical">Aksi</flux:button> --}}
        </div>
        <div class="flex items-center gap-2">
            <flux:icon.calendar />
            <p class="text-gray-500" x-text="'Diupload: ' + selectedPartCreatedAt"></p>
        </div>
    </div>

    <div class="space-y-4 min-w-max h-225">
        <template x-if="selectedPart && revisionNote">
            <flux:callout variant="warning" icon="exclamation-circle" heading="Catatan sebelumnya: ">
                <flux:callout.text x-text="revisionNote"></flux:callout.text>
            </flux:callout>
        </template>

        <!-- Iframe Preview -->
        <template x-if="selectedFile">
            <iframe :src="selectedFile" loading="lazy" class="w-full h-full rounded-lg"></iframe>
        </template>
    </div>
</div>
@script
<script>
    Alpine.data('anyDocumentVersions', () => ({
        versions: @json($anyVersions),
        selectedVersion: 0,
        selectedPart: 0,

        init() {
            this.$watch('selectedVersion', () => {
                this.selectedPart = this.availableParts[0].part_number;
            });
        },

        get availableParts() {
            const versionData = this.versions.find(v => v.version === this.selectedVersion);
            return versionData ? versionData.details : [];
        },
        get selectedFile() {
            const selected = this.availableParts.find(d => d.part_number === this.selectedPart);
            return selected ? selected.file_path : '';
        },
        get revisionNote() {
            const selected = this.availableParts.find(d => d.part_number === this.selectedPart);
            return selected ? selected.revision_note : '';
        },
        get selectedPartCreatedAt() {
            const selected = this.availableParts.find(d => d.part_number === this.selectedPart);
            return selected ? selected.created_at : '';
        }
    }))
</script>
@endscript