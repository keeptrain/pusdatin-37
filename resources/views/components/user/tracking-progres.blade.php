@props(['status'])

@php
    // Ambil teks label dari objek status
    $label = strtolower($status->label());

    // Default width
    $width = 0;

    switch ($label) {
        case 'pending':
            $width = 5;
            break;
        case 'disposition':
            $width = 25;
            break;
        case 'process':
            $width = 50;
            break;
        case 'replied':
            $width = 60;
            break;
        case 'approved by kasatpel':
            $width = 75;
            break;
        case 'approved by kapusdatin':
            $width = 100;
            break;
    }
@endphp
<div class="max-w-screen-xl px-4 lg:px-0 mx-auto">
    <div class="w-fulll p-6 bg-white rounded-lg border">
        <h2 class="text-lg font-bold text-gray-800 mb-8">Tracking Progress</h2>

        <!-- ini buat di desktop di hidden di mobile -->
        <div class="relative hidden md:block">
            <!-- Progress Line -->
            <div class="absolute top-6 left-0 w-full h-0.5 bg-gray-300">
                <!-- ubah persenan di width nya untuk progres yang selesai -->
                <div class="absolute top-0 left-0 h-full bg-[#364872] progres-bar" style="width: {{ $width }}%;"></div>
            </div>

            <!-- Steps -->
            <div class="flex justify-between relative">
                <!-- Step 1: Document Diterima -->
                <div class="flex flex-col items-center">

                    <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2 bg-gray-800">
                        <!-- FileEdit Icon from Lucide -->
                        <x-lucide-file class="w-6 text-white" />
                    </div>

                    <!-- Step Indicator -->
                    <div class="flex items-center justify-center">
                        <div class="w-3 h-3 rounded-full z-20 border-2 border-white bg-gray-800"></div>
                    </div>

                    <!-- Step Name -->
                    <p class="text-center text-sm font-medium mt-2 w-24">Dokumen Diterima</p>
                </div>

                <!-- Step 2: Document Disposisi -->
                <div class="flex flex-col items-center">
                    <!-- Icon Circle -->
                    <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2 bg-gray-800">
                        <!-- Search Icon from Lucide -->
                        <x-lucide-search class="w-6 text-white" />
                    </div>

                    <!-- Step Indicator -->
                    <div class="flex items-center justify-center">
                        <div class="w-3 h-3 rounded-full z-20 border-2 border-white bg-gray-800"></div>
                    </div>

                    <!-- Step Name -->
                    <p class="text-center text-sm font-medium mt-2 w-24">Dokumen Disposisi</p>
                </div>

                <!-- Step 3: Dokumen di proses -->
                <div class="flex flex-col items-center">
                    <!-- Icon Circle -->
                    <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2 bg-gray-800">
                        <!-- Code2 Icon from Lucide -->
                        <x-lucide-file-clock class="w-6 text-white" />
                    </div>

                    <!-- Step Indicator -->
                    <div class="flex items-center justify-center">
                        <div class="w-3 h-3 rounded-full z-20 border-2 border-white bg-white"></div>
                    </div>

                    <!-- Step Name -->
                    <p class="text-center text-sm font-medium mt-2 w-24">Dokumen Di Proses</p>
                </div>

                @if ($status === 'replied')
                    <!-- Step 4: Dokumen Revisi -->
                    <div class="flex flex-col items-center">
                        <!-- Icon Circle -->
                        <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2 bg-orange-600">
                            <!-- Code2 Icon from Lucide -->
                            <x-lucide-folder-cog class="w-6 text-white" />
                        </div>

                        <!-- Step Indicator -->
                        <div class="flex items-center justify-center">
                            <div class="w-3 h-3 rounded-full z-20 border-2 border-white bg-orange-600"></div>
                        </div>

                        <!-- Step Name -->
                        <p class="text-center text-sm font-medium mt-2 w-24">Dokumen Revisi</p>
                    </div>
                @endif

                <!-- Step 5: Finish -->
                <div class="flex flex-col items-center">
                    <!-- Icon Circle -->
                    <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2 bg-gray-400">
                        <!-- CheckCircle Icon from Lucide -->
                        <x-lucide-check-circle class="w-6 text-white" />
                    </div>

                    <!-- Step Indicator -->
                    <div class="flex items-center justify-center">
                        <div class="w-3 h-3 rounded-full z-20 border-2 border-white bg-white"></div>
                    </div>

                    <!-- Step Name -->
                    <p class="text-center text-sm font-medium mt-2 w-24">Disetujui Kasatpel</p>
                </div>

                <!-- Step 5: Finish -->
                <div class="flex flex-col items-center">
                    <!-- Icon Circle -->
                    <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2 bg-green-500">
                        <!-- CheckCircle Icon from Lucide -->
                        <x-lucide-check-circle class="w-6 text-white" />
                    </div>

                    <!-- Step Indicator -->
                    <div class="flex items-center justify-center">
                        <div class="w-3 h-3 rounded-full z-20 border-2 border-white bg-white"></div>
                    </div>

                    <!-- Step Name -->
                    <p class="text-center text-sm font-medium mt-2 w-24">Disetujui KaPusdatin</p>
                </div>

                <!-- Step 5.1: Reject -->
                <div class=" flex-col items-center hidden ">
                    <!-- Icon Circle -->
                    <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2 bg-red-500">
                        <!-- CheckCircle Icon from Lucide -->
                        <x-lucide-circle-x class="w-6 text-white" />
                    </div>

                    <!-- Step Indicator -->
                    <div class="flex items-center justify-center">
                        <div class="w-3 h-3 rounded-full z-20 border-2 border-white bg-red-500"></div>
                    </div>

                    <!-- Step Name -->
                    <p class="text-center text-sm font-medium mt-2 w-24">Dokumen Ditolak</p>
                </div>
            </div>
        </div>

        <!-- hanya di display di mobile -->
        <div class="md:hidden">
            <div class="relative">
                <!-- Vertical Progress Line -->
                <div class="absolute top-0 bottom-0 left-6 w-0.5 h-full bg-gray-300">
                    <!-- Change the height percentage based on current step (0%, 33%, 66%, 100%) -->
                    <div class="absolute top-0 left-0 w-full bg-red-500" style="height: 33%;"></div>
                </div>

                <!-- Vertical Steps -->
                <div class="flex flex-col">
                    <!-- Step 1: Document Received -->
                    <div class="flex items-start mb-8 relative">
                        <div class="flex flex-col items-center mr-4">
                            <!-- Icon Circle -->
                            <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2 bg-gray-800">
                                <!-- FileEdit Icon from Lucide -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="w-6 h-6 text-white">
                                    <path d="M4 13.5V4a2 2 0 0 1 2-2h8.5L20 7.5V20a2 2 0 0 1-2 2h-5.5"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <path d="M10.42 12.61a2.1 2.1 0 1 1 2.97 2.97L7.95 21 4 22l.99-3.95 5.43-5.44z">
                                    </path>
                                </svg>
                            </div>

                            <!-- Step Indicator -->
                            <div class="absolute left-6 top-6">
                                <div
                                    class="w-3 h-3 rounded-full z-20 border-2 border-white bg-red-500 transform -translate-x-1/2">
                                </div>
                            </div>
                        </div>

                        <!-- Step Name -->
                        <div class="pt-3">
                            <p class="font-medium">Document Received</p>
                        </div>
                    </div>

                    <!-- Step 2: Document Verification -->
                    <div class="flex items-start mb-8 relative">
                        <div class="flex flex-col items-center mr-4">
                            <!-- Icon Circle -->
                            <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2 bg-gray-800">
                                <!-- Search Icon from Lucide -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="w-6 h-6 text-white">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.3-4.3"></path>
                                </svg>
                            </div>

                            <!-- Step Indicator -->
                            <div class="absolute left-6 top-6">
                                <div
                                    class="w-3 h-3 rounded-full z-20 border-2 border-white bg-red-500 transform -translate-x-1/2">
                                </div>
                            </div>
                        </div>

                        <!-- Step Name -->
                        <div class="pt-3">
                            <p class="font-medium">Document Verification</p>
                        </div>
                    </div>

                    <!-- Step 3: Development Process -->
                    <div class="flex items-start mb-8 relative">
                        <div class="flex flex-col items-center mr-4">
                            <!-- Icon Circle -->
                            <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2 bg-gray-800">
                                <!-- Code2 Icon from Lucide -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="w-6 h-6 text-white">
                                    <path d="m18 16 4-4-4-4"></path>
                                    <path d="m6 8-4 4 4 4"></path>
                                    <path d="m14.5 4-5 16"></path>
                                </svg>
                            </div>

                            <!-- Step Indicator -->
                            <div class="absolute left-6 top-6">
                                <div
                                    class="w-3 h-3 rounded-full z-20 border-2 border-white bg-white transform -translate-x-1/2">
                                </div>
                            </div>
                        </div>

                        <!-- Step Name -->
                        <div class="pt-3">
                            <p class="font-medium">Development Process</p>
                        </div>
                    </div>

                    <!-- Step 4: Finish Project -->
                    <div class="flex items-start relative">
                        <div class="flex flex-col items-center mr-4">
                            <!-- Icon Circle -->
                            <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2 bg-gray-400">
                                <!-- CheckCircle Icon from Lucide -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="w-6 h-6 text-white">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                            </div>

                            <!-- Step Indicator -->
                            <div class="absolute left-6 top-6">
                                <div
                                    class="w-3 h-3 rounded-full z-20 border-2 border-white bg-white transform -translate-x-1/2">
                                </div>
                            </div>
                        </div>

                        <!-- Step Name -->
                        <div class="pt-3">
                            <p class="font-medium">Finish Project</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-user.tracking-list :activity="$this->activities" />
            
    </div>
</div>