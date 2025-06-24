<?php

namespace App\Livewire\Requests\InformationSystem;

use App\Models\InformationSystemRequest;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class Index extends DataTableComponent
{
    // Model utama
    protected $model = InformationSystemRequest::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('created_at', 'desc')
            ->setPerPage(10)
            // ➜ Tambahkan ini:
            ->setTableRowUrl(fn($row) => route('is.show', $row->id))
            // (opsional) target _self sudah default, bisa di-skip
            ->setTableRowUrlTarget(fn() => '_self');
    }

    // Query builder untuk ambil data
    public function builder(): Builder
    {
        return InformationSystemRequest::query()
            ->with('user'); // eager load relasi user bila diperlukan
    }

    // Definisikan kolom-kolom tabel
    // ...
    public function columns(): array
    {
        return [
            Column::make(__('ID'), 'id')
                ->sortable(),

            Column::make(__('Judul'), 'title')
                ->searchable()
                ->sortable()
                ->format(fn($value, $row) => sprintf(
                    '<a href="%s" class="block w-full h-full text-blue-600 hover:underline">%s</a>',
                    route('is.show', $row->id),
                    e($value)
                ))
                ->html(),
            Column::make(__('Referensi'), 'reference_number')
                ->searchable()
                ->sortable(),

            Column::make(__('Status'), 'status')
                ->format(fn($value, $row) => $row->status->label() ?? $value)
                ->sortable(),

            // === UBAH INI ===
            Column::make(__('Divisi'), 'current_division')
                ->format(fn($value, $row) => $row->division_label)
                ->sortable(),

            Column::make(__('Dibuat pada'), 'created_at')
                ->sortable(),

        ];
    }
}
