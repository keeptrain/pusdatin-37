<?php

namespace App\Livewire\Letters\Data;


use Livewire\Component;
use App\Models\Letters\Letter;
use Livewire\Attributes\Locked;
use Illuminate\Support\Collection;

class Detail extends Component
{
    #[Locked]
    public int $letterId;

    public ?Letter $letter;

    public $uploads;

    public $directs;

    public $processedUploads;

    public $availablePart;

    public function mount(int $id)
    {
        $this->letterId = $id;
        $this->letter = Letter::with([
            'mapping.letterable' => function ($morphTo) {
                $morphTo->morphWith([
                    \App\Models\Letters\LetterUpload::class => [
                        'version'
                    ],
                    \App\Models\Letters\LetterDirect::class => [],
                ]);
            }
        ])->findOrFail($id);

        $this->processMappings();
        $this->processedUploads = $this->getProcessedUploadsProperty();
    }

    protected function processMappings()
    {
        $this->uploads = collect();
        $this->directs = collect();

        $this->letter->mapping->each(function ($mapping) {
            if ($mapping->letterable instanceof \App\Models\Letters\LetterUpload) {
                $this->uploads->push($mapping->letterable);
            } elseif ($mapping->letterable instanceof \App\Models\Letters\LetterDirect) {
                $this->directs->push($mapping->letterable);
            }
        });

        $this->uploads = $this->uploads->sortBy('part_number');

        $this->availablePart = $this->uploads
            ->filter(fn($part) => !empty($part->part_number))
            ->pluck('part_number')
            ->values()
            ->toArray();
    }

    private function getProcessedUploadsProperty(): Collection
    {
        $processed = new Collection();

        if ($this->letter && $this->letter->mapping->isNotEmpty()) {
            foreach ($this->letter->mapping as $map) {
                if ($map->letterable_type === \App\Models\Letters\LetterUpload::class && $map->letterable) {
                    $letterUpload = $map->letterable;

                    $activeVersionObject = $letterUpload->activeVersion->first();

                    $filePath = null;
                    if ($activeVersionObject) {
                        $filePath = $activeVersionObject->file_path;
                    }
                    $processed->push([
                        'part_number' => $letterUpload->part_number,
                        'file_path' => $filePath
                    ]);
                }
            }
        }
        return $processed->sortBy('part_number');
    }
}
