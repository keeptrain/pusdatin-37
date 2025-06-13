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
    }
}" x-init="$watch('selectedVersion', () => { selectedPart = ''; })" class="p-4">

    <div class="items-center mb-4">
        <h2 class="text-md font-semibold text-gray-700">{{ $title }}</h2>
    </div>

    <div class="flex flex-1 mb-4 gap-4">
        <!-- Select Version -->
        <flux:select x-model.number="selectedVersion" placeholder="Pilih versi">
            <template x-for="version in versions" :key="version . version">
                <option :value="version . version" x-text="'Versi ' + version.version"></option>
            </template>
        </flux:select>

        <!-- Select Part Number -->
        <flux:select x-model.number="selectedPart" placeholder="Pilih dokumen">
            <template x-for="part in availableParts" :key="part . part_number">
                <option :value="part . part_number" x-text="part.part_number_label"></option>
            </template>
        </flux:select>
    </div>

    <!-- Iframe Preview -->
    <div x-show="selectedFile" class="h-[600px] bg-gray-100 rounded-lg overflow-hidden">
        <iframe :src="selectedFile" loading="lazy" class="w-full h-full"></iframe>
    </div>
</div>