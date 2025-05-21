<section>
    <flux:button :href="route('letter.table')" icon="arrow-long-left" variant="subtle">Back to Table</flux:button>

    <x-letters.detail-layout overViewRoute="letter.detail" activityRoute="letter.activity" :id="$letterId">
        {{-- Comparison Container --}}
        @php
            $mapping = $letter->documentUploads;
        @endphp

        @if ($mapping->first()->version)
            <div class="grid lg:grid-cols-2 gap-0 divide-x divide-gray-200">
                {{-- <x-documents.previous-document title="Versi sebelumnya" :mapping="$mapping"/> --}}

                <x-documents.current-document title="Versi saat ini" :mapping="$this->currentVersion()" />

            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end mt-6">
                <flux:button variant="primary">
                    Changes
                </flux:button>
            </div>
        @else
            <!-- Empty notificaiton -->
            <div class="flex flex-col items-center p-8">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-4">
                    <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No Revision</h3>
                <p class="text-center text-gray-500">You're all caught up! </p>
                <p class="text-center text-gray-500">Check back later for new revision.</p>
            </div>
        @endif

    </x-letters.detail-layout>
</section>