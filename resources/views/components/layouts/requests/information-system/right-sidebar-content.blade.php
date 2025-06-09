<div class="mb-6">
    <h4 class="text-gray-500 mb-1">Judul</h4>
    <p class="text-gray-800">
        {{ $letter->title }}
    </p>
</div>

<div class="mb-6">
    <h4 class="text-gray-500 mb-1">Penanggung Jawab</h4>
    <p class="text-gray-800">
        {{ $letter->user->name }}
    </p>
</div>

<div class="mb-6">
    <h4 class="text-gray-500 mb-1">Nomor Surat</h4>
    <p class="text-gray-800">
        {{ $letter->reference_number }}
    </p>
</div>

<div class="mb-6">
    <h4 class="text-gray-500 mb-1">Kontak</h4>
    <p class="text-gray-800">
        {{ $letter->user->contact }}
    </p>
</div>

<div class="mb-6">
    <h4 class="text-gray-500 mb-1">Seksi</h4>
    <p class="text-gray-800">
        {{ $letter->user->section }}
    </p>
</div>

<div class="mb-6">
    <h4 class="text-gray-500 mb-1">Tanggal dibuat</h4>
    <p class="text-gray-800">{{ $letter->createdAtWithTime() }}</p>
</div>

<div class="mb-6">
    <h4 class="text-gray-500 mb-1">Update terakhir</h4>
    <p class="text-gray-800">{{ $letter->updated_at }}</p>
</div>

<div class="mb-6">
    <h4 class="text-gray-500 mb-1">Status</h4>
    <flux:notification.status-badge :status="$letter->status"/>
</div>

@if (isset($letter->notes))
    <div class="mb-6">
        <h4 class="text-gray-500 mb-1">Catatan dari kapusdatin</h4>
        <ul class="space-y-1">
            @foreach ($letter->notes as $note)
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
        @foreach ($letter->documentUploads as $file)
            <div class="flex">
                <flux:icon.document class="size-5 mr-3"/>
                <button @click="partTab = '{{ $file->part_number }}'" class="text-start text-gray-700 hover:text-gray-900 cursor-pointer"
                    :class="{'border-b-2 border-blue-600 ': partTab === '{{ $file->part_number }}' }">{{ $file->part_number_label }}</button>
            </div>
        @endforeach
    </div>
</div>