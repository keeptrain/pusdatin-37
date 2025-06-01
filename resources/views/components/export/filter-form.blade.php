 @hasanyrole('si_verifier|data_verifier|pr_verifier|promkes_verifier')
 <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-10">
     <h1 class="text-xl font-semibold mb-4">Filter Export Data</h1>
     <form wire:submit.prevent="applyFilters" class="space-y-4">
         <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
             {{-- Start Date --}}
             <div class="md:col-span-3">
                 <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                 <div class="relative">
                     <input
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
 @endhasanyrole