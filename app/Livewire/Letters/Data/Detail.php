<?php

namespace App\Livewire\Letters\Data;


use Livewire\Component;
use App\Models\Letters\Letter;
use Livewire\Attributes\Locked;

class Detail extends Component
{
    #[Locked]
    public int $letterId;

    public ?Letter $letter;

    public $uploads;

    public $directs;

    public $showModal = false;

    public $activeRevision;

    public function mount(int $id)
    {
        $this->letterId = $id;
        $this->letter = Letter::with([
            'mapping.letterable' => function ($morphTo) {
                $morphTo->morphWith([
                    \App\Models\Letters\LetterUpload::class => [],
                    \App\Models\Letters\LetterDirect::class => [],
                ]);
            }
        ])->findOrFail($id);

        $this->processMappings();
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

        // Sorted part_number
    }

    public function repliedLetter()
    {
        $this->activeRevision = $this->letter->active_revision;
        $this->showModal = true;
    }

}
