<!-- Confirmation Modal -->
<div x-data="{ 
      showModal: false,
      deleteCount: 0,
      isDeleting: @entangle('isDeleting'),
      init() {
          this.$wire.on('confirm-delete', (data) => {
              this.deleteCount = data[0].count;
              this.showModal = true;
          });
      }
  }"
    x-show="showModal"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    @keydown.escape.window="showModal = false">

    <!-- Backdrop -->
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="showModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 transition-opacity bg-gray-500"
            @click="!isDeleting && (showModal = false)"
            style="opacity:40%"></div>

        <!-- Modal Content -->
        <div x-show="showModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">

            <div class="flex items-center mb-4">
                <div class="flex-shrink-0 w-10 h-10 mx-auto bg-red-100 rounded-full sm:mx-0">
                    <svg class="w-6 h-6 text-red-600 mt-2 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Konfirmasi Hapus Data</h3>
                </div>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus <span class="font-semibold text-red-600" x-text="deleteCount"></span> data yang dipilih?
                </p>
            </div>

            <!-- Loading State dalam Modal -->
            <div x-show="isDeleting" class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded">
                <div class="flex items-center">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600 mr-2"></div>
                    <span class="text-sm text-blue-700">Sedang menghapus data...</span>
                </div>
            </div>

            <div class="flex gap-3 justify-end">
                <button @click="!isDeleting && (showModal = false)"
                    :disabled="isDeleting"
                    :class="isDeleting ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    Batal
                </button>
                <button wire:click="deleteSelected"
                    @click="showModal = false"
                    :disabled="isDeleting"
                    :class="isDeleting ? 'opacity-50 cursor-not-allowed' : 'hover:bg-red-700'"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                    <span x-show="!isDeleting">Hapus</span>
                    <span x-show="isDeleting" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menghapus...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>