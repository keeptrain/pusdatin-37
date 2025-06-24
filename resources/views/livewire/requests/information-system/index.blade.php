<div class="lg:p-3">
    <flux:heading size="xl" level="1">{{ __('Daftar') }}</flux:heading>
    <flux:heading size="lg" level="2" class="mb-6">
        {{ __('Permohonan Layanan Sistem Informasi & Data') }}
    </flux:heading>

    <div class="overflow-auto">
        {{-- Render tabel dari Rappasoft --}}
        {{ $this->table }}
    </div>
</div>