<div class="border border-gray-200 rounded-lg p-4">
    <div class="flex items-center space-x-3 mb-4">
        <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>
        <div class="flex-1">
            <h3 class="text-base font-medium text-gray-900 mb-1">PDF Report</h3>
        </div>
    </div>

    <p class="text-sm text-gray-700 mb-4">
        Klik tombol Export as Pdf untuk unduh data permohonan sesuai akun anda dalam format pdf
    </p>
    @role('head_verifier')
    <a href=" {{ route('export.head_verifier.pdf') }}" target="_blank"
        class=" inline-flex items-center justify-center w-full px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
        Export as PDF
    </a>
    @endhasanyrole
    @role('si_verifier')
    <a href=" {{ route('export.si_verifier.pdf') }}" target="_blank"
        class=" inline-flex items-center justify-center w-full px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
        Export as PDF
    </a>
    @endhasanyrole
    @role('data_verifier')
    <a href="{{ route('export.data_verifier.pdf') }}" target="_blank"
        class=" inline-flex items-center justify-center w-full px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
        Export as PDF
    </a>
    @endhasanyrole
    @role('pr_verifier|promkes_verifier')
    <a href="{{ route('export.pr_verifier.pdf') }}" target="_blank"
        class=" inline-flex items-center justify-center w-full px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
        Export as PDF
    </a>
    @endhasanyrole
</div>