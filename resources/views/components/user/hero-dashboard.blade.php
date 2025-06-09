<section x-data="{ 
    openModal: false,
    hasReadSOP: false,
    sopConfirmed: false,
    sopPdfUrl: '{{ asset('pdf/sop-sistem-informasi-data.pdf') }}',
    
    init() {
        // Cek apakah user sudah pernah membaca SOP
        this.hasReadSOP = sessionStorage.getItem('read_sop') === 'true';
    },
    
    handleSIDataRequest() {
        if (this.hasReadSOP) {
           
            window.location.href = '{{ route('si-data.form') }}';
        } else {
           
            this.openModal = true;
        }
    },
    
    downloadSOP() {
        // Download PDF dari folder public
        const link = document.createElement('a');
        link.href = this.sopPdfUrl;  
        link.download = 'sop-sistem-informasi.pdf';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    },
    
    confirmReadSOP() {
        // Simpan ke sessionStorage
        sessionStorage.setItem('read_sop', 'true');
        this.hasReadSOP = true;
        
        // Redirect ke form
        window.location.href = '{{ route('si-data.form') }}';
    },
    
    closeModal() {
        this.openModal = false;
        this.sopConfirmed = false;
    }
}" x-cloak>
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Card 1: Layanan SI & Data --}}
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-blue-100 flex items-center justify-center">
                    <x-lucide-code class="w-6" />
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Layanan Sistem Informasi & Data</h3>
                <p class="mt-2 text-sm text-gray-600">Custom software solutions tailored to your needs</p>
                <button
                    @click="handleSIDataRequest()"
                    class="mt-4 inline-block bg-[#364872] text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-[#2c3751] transition">
                    Ajukan Permohonan
                </button>
            </div>

            {{-- Card 2: Layanan Kehumasan --}}
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-purple-100 flex items-center justify-center">
                    <x-lucide-file-video class="w-6" />
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Layanan Kehumasan</h3>
                <p class="mt-2 text-sm text-gray-600">Comprehensive media and communication solutions</p>
                <a href="{{ route('pr.form') }}"
                    class="mt-4 inline-block bg-[#364872] text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-[#2c3751] transition">
                    Ajukan Permohonan
                </a>
            </div>

        </div>
    </div>

    {{-- Modal SOP --}}
    <div x-show="openModal"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true">

        {{-- Background overlay --}}
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity "
                style="background-color: rgba(0,0,0,0.5);"
                @click="closeModal()"></div>

            {{-- Modal panel --}}
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-10 sm:align-middle sm:max-w-lg sm:w-full">

                {{-- Modal Header --}}
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <x-lucide-file-text class="h-6 w-6 text-blue-600" />
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                SOP Permohonan Sistem Informasi & Data
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Silakan download dan baca SOP terlebih dahulu sebelum mengisi form permohonan.
                                </p>
                            </div>
                        </div>
                        {{-- Close button --}}
                        <button @click="closeModal()"
                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                            <x-lucide-x class="h-5 w-5" />
                        </button>
                    </div>
                </div>

                {{-- Modal Body --}}
                <div class="bg-white px-4 pb-4 sm:px-6">
                    {{-- Download Card --}}
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                        <x-lucide-download class="mx-auto h-12 w-12 text-gray-400 mb-4" />
                        <h4 class="text-md font-medium text-gray-900 mb-2">
                            SOP Sistem Informasi & Data
                        </h4>
                        <p class="text-sm text-gray-500 mb-4">
                            Dokumen berisi prosedur dan persyaratan pengajuan layanan
                        </p>
                        <button @click="downloadSOP()"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                            <x-lucide-download class="h-4 w-4 mr-2" />
                            Download PDF
                        </button>
                    </div>

                    {{-- Checkbox konfirmasi --}}
                    <div class="mt-6">
                        <label class="flex items-start space-x-3">
                            <input type="checkbox"
                                x-model="sopConfirmed"
                                class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="text-sm text-gray-700">
                                Saya sudah membaca dan memahami SOP Permohonan Sistem Informasi & Data
                            </span>
                        </label>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="confirmReadSOP()"
                        :disabled="!sopConfirmed"
                        :class="sopConfirmed ? 
                                'bg-[#364872] hover:bg-[#2c3751] focus:ring-[#364872]' : 
                                'bg-gray-300 cursor-not-allowed'"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm transition">
                        Lanjut Isi Form
                    </button>
                    <button @click="closeModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>