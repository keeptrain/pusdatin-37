<div class="p-6">
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <flux:heading>
        Detail {{ $letter->category_type_name }} Letter
        <flux:notification.status-badge status="{{ $letter->status }}">
            {{ $letter->status }}
        </flux:notification.status-badge>
    </flux:heading>

    @if ($letterUpload != null)
        <iframe src="{{ asset($letterUpload) }}" width="600" height="600" class="mt-6" lazy>
            This browser does not support PDFs. Please download the PDF to view it: Download PDF
        </iframe>
    @endif

    <flux:text>{{ $letterDirect }}</flux:text>

</div>
