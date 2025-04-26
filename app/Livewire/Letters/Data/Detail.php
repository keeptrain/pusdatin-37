<?php

namespace App\Livewire\Letters\Data;

use App\States\Process;
use Livewire\Component;
use App\Models\Letters\Letter;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

    public function detailPage()
    {
        return redirect()->route('letter.edit', [$this->letterId]);
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

        // Sorted part_name to Part1,part2,part3
        $this->uploads = $this->uploads->sortBy(function ($upload) {
            return (int) filter_var($upload->part_name, FILTER_SANITIZE_NUMBER_INT);
        })->values();
    }

    public function processLetter(int $id)
    {
        if (Auth::user()->withoutRole('user')) {
            DB::transaction(function () {
                $this->letter->transitionToStatus(Process::class, '');
            });

            return redirect()->route('letter.table')->with([
                'status' => [
                    'variant' => 'success',
                    'message' => 'Letter has update to read status!'
                ]
            ]);
        } else {
            return redirect()->route('letter.detail', [$id])->with([
                'status' => [
                    'variant' => 'error',
                    'message' => 'Letter cannot update!'
                ]
            ]);
        }
    }

    public function backStatus()
    {
        $this->letter->status->transitionTo(Process::class);
    }
}
