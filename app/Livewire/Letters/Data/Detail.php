<?php

namespace App\Livewire\Letters\Data;


use Livewire\Component;
use App\Models\Letters\Letter;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;


class Detail extends Component
{
    #[Locked]
    public int $letterId;

    public ?Letter $letter;

    public $processedUploads;

    public function mount(int $id)
    {
        $this->letterId = $id;
        $this->letter = Letter::with([
            'documentUploads'
        ])->findOrFail($id);
    }

    #[Computed]
    public function detailItem()
    {
        return $this->letter->documentUploads->need_revision;
    }

    #[Computed]
    public function availablePart()
    {
        return $this->letter->documentUploads
            ->filter(fn($part) => !empty($part->part_number))
            ->pluck('part_number')
            ->values()
            ->toArray();
    }

    #[Computed]
    public function currentStatusLabel()
    {
        return $this->letter->status->label();
    }
}
