@hasanyrole('si_verifier|data_verifier|pr_verifier|promkes_verifier')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-10 flex flex-col gap-y-3">
    <div class="flex items-start gap-x-2  text-sm text-blue-700 bg-blue-50 rounded-lg p-3">
        <svg class="w-4 h-4 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
        </svg>
        <p>
            <span class="font-bold">Catatan: </span> untuk memfilter data permohonan berdasarkan rentan tanggal tertentu dan juga status permohonan anda bisa gunakan form dibawah
        </p>
    </div>
    <div>
        <h1 class="text-xl font-semibold mb-4">Filter Export Data</h1>
        <form wire:submit.prevent="applyFilters" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                {{-- Start Date --}}
                <div class="md:col-span-3">
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <div class="relative">
                        <input
                            required
                            type="date"
                            id="start_date"
                            wire:model="start_date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" />
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-calendar-alt text-gray-400 text-sm"></i>
                        </div>
                    </div>
                </div>

                {{-- End Date --}}
                <div class="md:col-span-3">
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <div class="relative">
                        <input
                            required
                            type="date"
                            id="end_date"
                            wire:model="end_date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" />
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-calendar-alt text-gray-400 text-sm"></i>
                        </div>
                    </div>
                </div>

                <!-- Request Status -->
                @hasanyrole('si_verifier|data_verifier')
                <div class="md:col-span-4">
                    <label for="request_status" class="block text-sm font-medium text-gray-700 mb-2">
                        Request Status
                    </label>
                    <div class="relative">
                        <select
                            id="status"
                            wire:model="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm appearance-none bg-white">
                            @foreach($statusOptions as $key => $label)
                            <option value="{{ $key }}" {{ $key === $status ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>

                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                        </div>
                    </div>
                </div>
                @endhasanyrole
                @hasanyrole('pr_verifier|promkes_verifier')
                <div class="md:col-span-4">
                    <label for="request_status" class="block text-sm font-medium text-gray-700 mb-2">
                        Request Status
                    </label>
                    <div class="relative">
                        <select
                            id="status"
                            wire:model="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm appearance-none bg-white">
                            @foreach($statusOptionsPr as $key => $label)
                            <option value="{{ $key }}" {{ $key === $status ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>

                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                        </div>
                    </div>
                </div>
                @endhasanyrole

                {{-- Tombol Apply Filters --}}
                <div class="md:col-span-2">
                    <button
                        type="submit"
                        class="w-full bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 flex items-center justify-center space-x-2">
                        <i class="fas fa-filter text-sm"></i>
                        <span>Apply Filters</span>
                    </button>
                </div>
            </div>


            <!-- <div class="flex justify-start pt-2">
                    <button
                        type="button"
                        wire:click="resetFilters"
                        class="text-sm text-gray-500 hover:text-gray-700 underline">
                        Reset Filters
                    </button>
                </div> -->
        </form>
    </div>
</div>
@endhasanyrole

@hasanyrole('head_verifier')
<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mt-10">
    <h2 class="text-lg font-semibold mb-4">Filter Data</h2>
    <form wire:submit.prevent="exportHeadVerifier" class="grid grid-cols-12 gap-4 items-end">

        {{-- Data Source --}}
        <div class="col-span-3">
            <label for="source" class="block text-sm font-medium text-gray-700 mb-1">Data Source</label>
            <select
                id="source"
                wire:model.change="source"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                <option value="">Pilih Source</option>
                <option value="letter">Letters</option>
                <option value="pr">Public Relation</option>
            </select>
        </div>

        {{-- Start Date --}}
        <div class="col-span-3">
            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
            <input
                type="date"
                id="start_date"
                wire:model="start_date"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm" />
        </div>

        {{-- End Date --}}
        <div class="col-span-3">
            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
            <input
                type="date"
                id="end_date"
                wire:model="end_date"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm" />
        </div>

        {{-- Status --}}
        <div class="col-span-3">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select wire:model="status" wire:loading.attr="disabled" class="w-full px-3 py-2 border rounded-md text-sm">
                @if($source == 'letter')
                @foreach($statusOptions as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
                @elseif($source == 'pr')
                @foreach($statusOptionsPr as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
                @else
                <option value="">Pilih Source terlebih dahulu</option>
                @endif
            </select>
        </div>

        {{-- Apply Filters --}}
        <div class="col-span-2">
            <button
                type="submit"
                class="w-full bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 flex items-center justify-center space-x-2">
                <i class="fas fa-filter text-sm"></i>
                <span>Apply Filters</span>
            </button>
        </div>

    </form>
</div>
@endhasanyrole