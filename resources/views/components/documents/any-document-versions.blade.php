<div x-data="{
    versions: {{ $anyVersions }},
    selectedVersion: '',
    selectedPart: '',
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
    }
}" x-init="$watch('selectedVersion', () => { selectedPart = ''; })" class="p-4">

    <div class="items-center mb-4">
        <h2 class="text-md font-semibold text-gray-700">{{ $title }}</h2>
    </div>

    <div class="flex flex-1 mb-4 gap-4">
        <!-- Select Version -->
        <flux:select size="sm" x-model.number="selectedVersion" placeholder="Pilih versi">
            <template x-for="version in versions" :key="version . version">
                <option :value="version . version" x-text="'Versi ' + version.version"></option>
            </template>
        </flux:select>

        <!-- Select Document -->
        <flux:select size="sm" x-model.number="selectedPart" placeholder="Pilih dokumen">
            <template x-for="part in availableParts" :key="part . part_number">
                <option :value="part . part_number" x-text="part.part_number_label"></option>
            </template>
        </flux:select>
    </div>

    <!-- Iframe Preview -->
    <template x-if="selectedFile">
        <div class="h-[700px] bg-gray-100 rounded-xl overflow-hidden">
            <iframe :src="selectedFile" loading="lazy" class="w-full h-full"></iframe>
        </div>
    </template>

    <!-- Revision Note -->

    <template x-if="selectedPart">
        <flux:callout variant="warning" icon="exclamation-circle" heading="Catatan sebelumnya: " class="mt-2">
            <flux:callout.text x-text="revisionNote"></flux:callout.text>
        </flux:callout>
        {{-- <div class="border-l-2 border-amber-500 bg-amber-50 mt-2">
            <div class="p-2 bg-amber-50 flex flex-1 items-center">
                <!-- Icon -->
                <flux:icon.exclamation-circle class="text-amber-600 dark:text-amber-300 w-5 h-5" />

                <!-- Subheading -->
                <flux:subheading size="lg" class="ml-2 text-amber-600">Catatan: </flux:subheading>

                <!-- Heading -->
                <flux:subheading size="lg" class="ml-2 text-amber-800" x-text="revisionNote"></flux:subheading>
            </div>
        </div> --}}
    </template>
</div>