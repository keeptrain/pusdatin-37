<div class="bg-white rounded-lg border border-gray-200 p-6 mt-10 flex flex-col gap-y-3">
    <div class="space-y-4">
        <h2 class="text-lg mb-4">Filter Data</h2>
        <div class="flex items-start gap-x-2 text-sm text-blue-700 bg-blue-50 rounded-lg p-3">
            <svg class="w-4 h-4 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                    clip-rule="evenodd"></path>
            </svg>
            <p>
                <span class="font-bold">Catatan: </span> untuk memfilter data permohonan berdasarkan rentan tanggal
                tertentu
                dan juga status permohonan anda bisa gunakan form dibawah
            </p>
        </div>

        <form wire:submit="customFilters" class="grid grid-cols-12 gap-4 items-end">
            {{-- Data Source --}}
            @hasrole('head_verifier')
            <div class="col-span-3">
                <label for="service" class="block text-sm font-medium text-gray-700 mb-1">Jenis Layanan</label>
                <select id="service" wire:model="service"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="">Pilih Jenis Layanan</option>
                    <option value="all">Semua Layanan</option>
                    <option value="si">Sistem Informasi Dan Data</option>
                    <option value="pr">Kehumasan</option>
                </select>
                @error('service')
                    <div class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            @endhasrole

            {{-- Start Date --}}
            <div class="col-span-3">
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Dari tanggal</label>
                <input type="date" id="start_date" wire:model="startAt"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                @error('startAt')
                    <div class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- End Date --}}
            <div class="col-span-3">
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Sampai dengan tanggal</label>
                <input type="date" id="end_date" wire:model="endAt"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                @error('endAt')
                    <div class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>


            {{-- Status --}}
            <div class="col-span-3">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status permohonan</label>
                <select wire:model="status" wire:loading.attr="disabled"
                    class="w-full px-3 py-2 border rounded-md text-sm">
                    @unlessrole('head_verifier')
                    <option value="">Pilih Status</option>
                    @foreach($statusOptions as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                    @endunlessrole
                </select>
                @error('status')
                    <div class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Apply Filters --}}
            <div class="col-span-2">
                <button type="submit"
                    class="w-full bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 flex items-center justify-center space-x-2">
                    <i class="fas fa-filter text-sm"></i>
                    <span>Terapkan Filter</span>
                </button>
            </div>
        </form>
    </div>
</div>