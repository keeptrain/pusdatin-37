@props(['status'])

@php
// Ambil teks label dari objek status
$label = strtolower($status->label());

// Default width
$width = 0;

$step1ActiveStatuses = ['permohonan masuk', 'disposition', 'proses', 'replied', 'approved by kasatpel', 'approved by kapusdatin'];
$step2ActiveStatuses = ['disposition', 'proses', 'replied', 'approved by kasatpel', 'approved by kapusdatin'];
$step3ActiveStatuses = ['proses', 'replied', 'approved by kasatpel', 'approved by kapusdatin'];
$step4ActiveStatuses = ['replied', 'approved by kasatpel', 'approved by kapusdatin'];
$step5ActiveStatuses = ['approved by kasatpel', 'approved by kapusdatin'];
$step6ActiveStatuses = ['approved by kapusdatin'];
$stepFail = ['rejected'];


$step1IsActive = in_array($label, $step1ActiveStatuses);
$step2IsActive = in_array($label, $step2ActiveStatuses);
$step3IsActive = in_array($label, $step3ActiveStatuses);
$step4IsActive = in_array($label, $step4ActiveStatuses);
$step5IsActive = in_array($label, $step5ActiveStatuses);
$step6IsActive = in_array($label, $step6ActiveStatuses);
$stepFailActive = in_array($label, $stepFail);

switch ($label) {
case 'permohonan masuk':
$width = 5;
$height = 3;
break;
case 'disposition':
$width = 25;
$height = 23;
break;
case 'process':
$width = 50;
$height = 43;
break;
case 'replied':
$width = 60;
$height = 50;
break;
case 'approved by kasatpel':
$width = 75;
$height = 60;
break;
case 'approved by kapusdatin':
$width = 100;
$height = 100;
break;
case 'rejected':
$width = 100;
$height = 100;
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
                <div class="absolute top-0 left-0 h-full {{ $stepFailActive ? 'bg-red-500' : 'bg-[#364872] ' }} progres-bar" style="width: {{ $width }}%;"></div>
            </div>

            <!-- Steps -->
            <div class="flex justify-between relative">
                <!-- Step 1: Document Diterima -->
                <div class="flex flex-col items-center">

                    <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2 circle-bg  {{ $step1IsActive ? 'bg-gray-800' : 'bg-gray-400' }}">
                        <!-- Icon from Lucide -->
                        <x-lucide-file class="w-6 text-white" />
                    </div>

                    <!-- Step Indicator -->
                    <div class="flex items-center justify-center">
                        <div class="w-3 h-3 rounded-full z-20 border-2 border-white step-indi  {{ $step1IsActive ? 'bg-gray-800' : 'bg-gray-400' }}"></div>
                    </div>

                    <!-- Step Name -->
                    <p class="text-center text-sm font-medium mt-2 w-24">Dokumen Diterima</p>
                </div>

                <!-- Step 2: Document Disposisi -->
                <div class="flex flex-col items-center">
                    <!-- Icon Circle -->
                    <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2  {{ $step2IsActive ? 'bg-gray-800' : 'bg-gray-400' }}">
                        <!--  Icon from Lucide -->
                        <x-lucide-search class="w-6 text-white" />
                    </div>

                    <!-- Step Indicator -->
                    <div class="flex items-center justify-center">
                        <div class="w-3 h-3 rounded-full z-20 border-2 border-white  {{ $step2IsActive ? 'bg-gray-800' : 'bg-gray-400' }}"></div>
                    </div>

                    <!-- Step Name -->
                    <p class="text-center text-sm font-medium mt-2 w-24">Dokumen Disposisi</p>
                </div>

                <!-- Step 3: Dokumen di proses -->
                <div class="flex flex-col items-center">
                    <!-- Icon Circle -->
                    <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2  {{ $step3IsActive ? 'bg-gray-800' : 'bg-gray-400' }}">
                        <!-- Icon from Lucide -->
                        <x-lucide-file-clock class="w-6 text-white" />
                    </div>

                    <!-- Step Indicator -->
                    <div class="flex items-center justify-center">
                        <div class="w-3 h-3 rounded-full z-20 border-2 border-white  {{ $step3IsActive ? 'bg-gray-800' : 'bg-gray-400' }}"></div>
                    </div>

                    <!-- Step Name -->
                    <p class="text-center text-sm font-medium mt-2 w-24">Dokumen Di Proses</p>
                </div>


                <!-- Step 4: Dokumen Revisi -->
                <div class=" {{ $step4IsActive ? 'flex' : 'hidden' }} flex-col items-center">
                    <!-- Icon Circle -->
                    <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2  {{ $step4IsActive ? 'bg-orange-600' : 'bg-gray-400' }}">
                        <!-- Icon from Lucide -->
                        <x-lucide-folder-cog class="w-6 text-white" />
                    </div>

                    <!-- Step Indicator -->
                    <div class="flex items-center justify-center">
                        <div class="w-3 h-3 rounded-full z-20 border-2 border-white  {{ $step4IsActive ? 'bg-orange-600' : 'bg-gray-400' }}"></div>
                    </div>

                    <!-- Step Name -->
                    <p class="text-center text-sm font-medium mt-2 w-24">Dokumen Revisi</p>
                </div>


                <!-- Step 5: Finish -->
                <div class="{{ $stepFailActive ? 'hidden' : 'flex' }} flex-col items-center">
                    <!-- Icon Circle -->
                    <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2 {{ $step5IsActive ? 'bg-green-500' : 'bg-gray-400' }}">
                        <!-- Icon from Lucide -->
                        <x-lucide-check-circle class="w-6 text-white" />
                    </div>

                    <!-- Step Indicator -->
                    <div class="flex items-center justify-center">
                        <div class="w-3 h-3 rounded-full z-20 border-2 border-white  {{ $step5IsActive ? 'bg-green-500' : 'bg-gray-400' }}"></div>
                    </div>

                    <!-- Step Name -->
                    <p class="text-center text-sm font-medium mt-2 w-24">Disetujui Kasatpel</p>
                </div>

                <!-- Step 5: Finish -->
                <div class="{{ $stepFailActive ? 'hidden' : 'flex' }} flex-col items-center">
                    <!-- Icon Circle -->
                    <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2 {{ $step6IsActive ? 'bg-green-500' : 'bg-gray-400' }}">
                        <!-- Icon from Lucide -->
                        <x-lucide-check-circle class="w-6 text-white" />
                    </div>

                    <!-- Step Indicator -->
                    <div class="flex items-center justify-center">
                        <div class="w-3 h-3 rounded-full z-20 border-2 border-white {{ $step6IsActive ? 'bg-green-500' : 'bg-gray-400' }}"></div>
                    </div>

                    <!-- Step Name -->
                    <p class="text-center text-sm font-medium mt-2 w-24">Disetujui KaPusdatin</p>
                </div>

                <!-- Step 5.1: Reject -->
                <div class="{{ $stepFailActive ? 'flex' : 'hidden' }} flex-col items-center">
                    <!-- Icon Circle -->
                    <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2 bg-red-500">
                        <!-- Icon from Lucide -->
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
                <div class="absolute top-0 bottom-0 left-6 w-0.5 h-[95%] bg-gray-300">
                    <!-- Change the height percentage based on current step (0%, 33%, 66%, 100%) -->
                    <div class="absolute top-0 left-0 w-full bg-gray-800" style="height: {{ $height }}%;"></div>
                </div>

                <!-- Vertical Steps -->
                <div class="flex flex-col">
                    <!-- Step 1: Dokumen Diterima -->
                    <div class="flex items-start mb-8 relative">
                        <div class="flex flex-col items-center mr-4">
                            <!-- Icon Circle -->
                            <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2 bg-gray-800">
                                <!-- FileEdit Icon from Lucide -->
                                <x-lucide-file class="w-6 h-6 text-white" />
                            </div>

                            <!-- Step Indicator -->
                            <div class="absolute left-6 top-6">
                                <div
                                    class="w-3 h-3 rounded-full z-20 border-2 border-white bg-gray-800 transform -translate-x-1/2">
                                </div>
                            </div>
                        </div>

                        <!-- Step Name -->
                        <div class="pt-3">
                            <p class="font-medium">Dokumen Diterima</p>
                        </div>
                    </div>

                    <!-- Step 2: Dokumen Disposisi -->
                    <div class="flex items-start mb-8 relative">
                        <div class="flex flex-col items-center mr-4">
                            <!-- Icon Circle -->
                            <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2 bg-gray-800">
                                <!-- Search Icon from Lucide -->
                                <x-lucide-search class="w-6 text-white" />
                            </div>

                            <!-- Step Indicator -->
                            <div class="absolute left-6 top-6">
                                <div
                                    class="w-3 h-3 rounded-full z-20 border-2 border-white bg-gray-800 transform -translate-x-1/2">
                                </div>
                            </div>
                        </div>

                        <!-- Step Name -->
                        <div class="pt-3">
                            <p class="font-medium">Dokumen Disposisi</p>
                        </div>
                    </div>

                    <!-- Step 3: Dokumen Diproses -->
                    <div class="flex items-start mb-8 relative">
                        <div class="flex flex-col items-center mr-4">
                            <!-- Icon Circle -->
                            <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2 bg-gray-800">
                                <x-lucide-file-clock class="w-6 text-white" />
                            </div>

                            <!-- Step Indicator -->
                            <div class="absolute left-6 top-6">
                                <div
                                    class="w-3 h-3 rounded-full z-20 border-2 border-white bg-gray-800 transform -translate-x-1/2">
                                </div>
                            </div>
                        </div>

                        <!-- Step Name -->
                        <div class="pt-3">
                            <p class="font-medium">Dokumen Diproses</p>
                        </div>
                    </div>

                    <!-- Step 4: Dokumen Revisi -->
                    <div class="hidden items-start mb-8 relative">
                        <div class="flex flex-col items-center mr-4">
                            <!-- Icon Circle -->
                            <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2 bg-orange-600">
                                <x-lucide-folder-cog class="w-6 text-white" />
                            </div>

                            <!-- Step Indicator -->
                            <div class="absolute left-6 top-6">
                                <div
                                    class="w-3 h-3 rounded-full z-20 border-2 border-white bg-orange-600 transform -translate-x-1/2">
                                </div>
                            </div>
                        </div>

                        <!-- Step Name -->
                        <div class="pt-3">
                            <p class="font-medium">Dokumen Revisi</p>
                        </div>
                    </div>

                    <!-- Step 5: Finish -->
                    <div class="flex items-start mb-8 relative">
                        <div class="flex flex-col items-center mr-4">
                            <!-- Icon Circle -->
                            <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2 bg-green-500">
                                <!-- CheckCircle Icon from Lucide -->
                                <x-lucide-check-circle class="w-6 text-white" />
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
                            <p class="font-medium">Disetujui Kasatpel</p>
                        </div>
                    </div>

                    <!-- Step 5: Finish -->
                    <div class="flex items-start mb-8 relative">
                        <div class="flex flex-col items-center mr-4">
                            <!-- Icon Circle -->
                            <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2 bg-green-500">
                                <!-- CheckCircle Icon from Lucide -->
                                <x-lucide-check-circle class="w-6 text-white" />
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
                            <p class="font-medium">Disetujui KaPusdatin</p>
                        </div>
                    </div>

                    <!-- Step 5.1: reject -->
                    <div class="flex items-start  relative">
                        <div class="flex flex-col items-center mr-4">
                            <!-- Icon Circle -->
                            <div class="w-12 h-12 flex items-center justify-center rounded-full z-10 mb-2 bg-red-500">
                                <!-- CheckCircle Icon from Lucide -->
                                <x-lucide-circle-x class="w-6 text-white" />
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
                            <p class="font-medium">Dokumen Ditolak</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-user.tracking-list :activity="$this->activities" />

    </div>
</div>