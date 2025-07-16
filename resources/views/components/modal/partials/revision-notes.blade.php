@foreach([1, 2, 3, 4, 5] as $part)
    <template x-if="revisionPart.includes('{{ $part }}')">
        <div class="mb-4">
            @php
                $placeholders = [
                    1 => "Catatan untuk dokumen identifikasi aplikasi",
                    2 => "Catatan untuk SOP aplikasi",
                    3 => "Catatan untuk pakta integritas implementasi",
                    4 => "Catatan untuk form RFC pusdatinkes",
                    5 => "Catatan untuk surat perjanjian kerahasiaan"
                ];
            @endphp

            <flux:textarea wire:model.defer="form.revisionNotes.{{ $part }}" cols="6" rows="2"
                placeholder="{{ $placeholders[$part] }}" resize="vertical" />

            @error("form.revisionNotes.{$part}")
                <div x-show="revisionPart.includes('{{ $part }}')" class="mt-1">
                    <flux:text variant="strong" color="red">
                        {{ $message }}
                    </flux:text>
                </div>
            @enderror
        </div>
    </template>
@endforeach