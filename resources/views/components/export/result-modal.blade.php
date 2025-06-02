@if($showModal)
<div
    x-data="{ open: true }"
    x-show="open"
    class="fixed inset-0 z-50 flex items-center justify-center"
    style="background-color: rgba(0, 0, 0, 0.5);">
    <div
        x-show="open"
        x-transition.opacity
        class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
        {{-- Header Modal --}}
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold">Download Filtered Data</h3>
            <button
                @click="open = false; $wire.resetFilters()"
                class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Isi Modal --}}
        <div class="px-6 py-4">
            <p class="text-sm text-gray-700 mb-4">
                Data akan diekspor sesuai dengan pilihan berikut:
            </p>
            <ul class="text-gray-800 mb-4 space-y-1">

                <li><strong>Start Date:</strong> {{ $startDate ?? '—' }}</li>
                <li><strong>End Date:</strong> {{ $endDate   ?? '—' }}</li>
                <li><strong>Status:</strong>
                    @if(!$status || $status === 'all')
                    All Status
                    @else
                    @switch($status)
                    {{-- Status “Letter” --}}
                    @case('disposition') Disposition @break
                    @case('process') Process @break
                    @case('replied') Replied @break
                    @case('approved_kasatpel') Approved Kasatpel @break
                    @case('replied_kapusdatin') Replied Kapusdatin @break
                    @case('approved_kapusdatin') Approved Kapusdatin @break
                    @case('rejected') Rejected @break

                    {{-- Status “PublicRelationRequest” --}}
                    @case('antrian_promkes') Antrian Promkes @break
                    @case('kurasi_promkes') Kurasi Promkes @break
                    @case('antrian_pusdatin') Antrian Pusdatin @break
                    @case('proses_pusdatin') Proses Pusdatin @break
                    @case('completed') Completed @break

                    @default Unknown
                    @endswitch
                    @endif
                </li>
            </ul>

            <div class="flex justify-end space-x-2">
                <button
                    @click="open = false; $wire.resetFilters()"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-sm">
                    Cancel
                </button>
                @hasanyrole('head_verifier')
                <a
                    href="{{ route('export.head_verifier.filtered', [
                        'start_date' => $startDate,
                        'end_date'   => $endDate,
                    ]) }}"
                    target="_blank"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm">
                    Download Excel
                </a>
                @endhasanyrole

                @hasanyrole('si_verifier')
                <a
                    href="{{ route('export.si_verifier.filtered', [
                        'start_date' => $startDate,
                        'end_date'   => $endDate,
                        'status'     => $status,
                    ]) }}"
                    target="_blank"
                    @click.prevent="
                        $wire.resetFilters();
                        open = false;
                        setTimeout(() => {
                            window.open(
                                '{{ route('export.si_verifier.filtered', [
                                    'start_date' => $startDate,
                                    'end_date'   => $endDate,
                                    'status'     => $status,
                                ]) }}',
                                '_blank'
                            );
                        }, 100);
                    "
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm">
                    Download Excel
                </a>
                @endhasanyrole

                @hasanyrole('data_verifier')
                <a
                    href="{{ route('export.data_verifier.filtered', [
                        'start_date' => $startDate,
                        'end_date'   => $endDate,
                        'status'     => $status,
                    ]) }}"
                    target="_blank"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm">
                    Download Excel
                </a>
                @endhasanyrole

                @hasanyrole('pr_verifier|promkes_verifier')
                <a
                    href="{{ route('export.pr_verifier.filtered', [
                        'start_date' => $startDate,
                        'end_date'   => $endDate,
                        'status'     => $status,
                    ]) }}"
                    target="_blank"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm">
                    Download Excel
                </a>
                @endhasanyrole
            </div>
        </div>
    </div>
</div>
@endif