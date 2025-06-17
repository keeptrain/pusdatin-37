<div class="mb-6">
    <h4 class="text-gray-500 mb-1">Judul</h4>
    <p class="text-gray-800">
        {{ $systemRequest->title }}
    </p>
</div>

<div class="mb-6">
    <h4 class="text-gray-500 mb-1">Penanggung Jawab</h4>
    <p class="text-gray-800">
        {{ $systemRequest->user->name }}
    </p>
</div>

<div class="mb-6">
    <h4 class="text-gray-500 mb-1">Nomor Surat</h4>
    <p class="text-gray-800">
        {{ $systemRequest->reference_number }}
    </p>
</div>

<div class="mb-6">
    <h4 class="text-gray-500 mb-1">Kontak</h4>
    <p class="text-gray-800">
        {{ $systemRequest->user->contact }}
    </p>
</div>

<div class="mb-6">
    <h4 class="text-gray-500 mb-1">Seksi</h4>
    <p class="text-gray-800">
        {{ $systemRequest->user->section }}
    </p>
</div>

<div class="mb-6">
    <h4 class="text-gray-500 mb-1">Tanggal dibuat</h4>
    <p class="text-gray-800">{{ $systemRequest->createdAtWithTime() }}</p>
</div>

<div class="mb-6">
    <h4 class="text-gray-500 mb-1">Update terakhir</h4>
    <p class="text-gray-800">{{ $systemRequest->updated_at }}</p>
</div>

<div class="mb-6">
    @if ($timeline)
        <h4 class="text-gray-500 mb-1">Timeline pengerjaan</h4>
        <p class="text-gray-800">{{ $timeline }}</p>
    @endif
</div>

<div class="mb-6">
    <h4 class="text-gray-500 mb-1">Status</h4>
    <flux:notification.status-badge :status="$systemRequest->status"/>
</div>

@if (isset($systemRequest->notes))
    <div class="mb-6">
        <h4 class="text-gray-500 mb-1">Catatan dari kapusdatin</h4>
        <ul class="space-y-1">
            @foreach ($systemRequest->notes as $note)
                <li class="flex items-start text-gray-800">
                    <span class=" mr-2">•</span>
                    <span>{{ $note }}</span>
                </li>
            @endforeach
        </ul>
        @if (auth()->user()->hasRole('head_verifier'))
        <form wire:submit="inputNotes">
            <div class="mt-2 flex items-center">
                <flux:textarea wire:model="notes" rows="1" 
                    placeholder="Catatan tambahan..." />
        
                <flux:button variant="subtle" type="submit"  size="sm"
                    class="ml-1">
                    <flux:icon.paper-airplane class="w-5 h-5" />
                </flux:button>
            </div>
        </form>
        @endif
    </div>
@endif

<div class="border-1 rounded-lg p-3">
    <h4 class="text-gray-500 mb-3">Kelengkapan dokumen</h4>
    <div class="space-y-3">
        @foreach ($systemRequest->documentUploads as $file)
            <div class="flex">
                <flux:icon.document class="size-5 mr-3"/>
                <button @click="partTab = '{{ $file->part_number }}'" class="text-start text-gray-700 hover:text-gray-900 cursor-pointer"
                    :class="{'border-b-2 border-blue-600 ': partTab === '{{ $file->part_number }}' }">{{ $file->part_number_label }}</button>
            </div>
        @endforeach
    </div>
</div>