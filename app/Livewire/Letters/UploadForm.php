<?php

namespace App\Livewire\Letters;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Models\Letters\Letter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Letters\LetterUpload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UploadForm extends Component
{
    use WithFileUploads;

    public $title = '';

    public $responsible_person = '';

    public $reference_number = '';

    public $files = [];

    public bool $fileReady = false;

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'responsible_person' => 'required|string',
            'reference_number' => 'required|string',
            'files.0' => 'required|file|mimes:pdf|max:1048',
            'files.1' => 'required|file|mimes:pdf|max:1048',
            'files.2' => 'required|file|mimes:pdf|max:1048'
        ];
    }

    public function messages()

    {
        return [
            'title.required' => 'Title is required',
            'responsible_person.required' => 'Responsible person is required',
            'reference_number.required' => 'Reference number is required',
        ];
    }

    public function mount() {}

    public function render()

    {
        return view('livewire.letters.upload-form');
    }

    public function nameFile(): string
    {
        $user = Auth::user();

        $dateTime = Carbon::now()->format('YmdHis');

        $nameWithoutExtension = pathinfo($this->file->getClientOriginalName(), PATHINFO_FILENAME);

        $fileName = $nameWithoutExtension . '-' . Str::slug($user->name) . '-' . $dateTime . '.pdf';

        return $fileName;
    }

    public function pathFile()
    {
        $extension = $this->file->guessExtension();
        return now()->timestamp . '.' . $extension;
    }

    public function save()
    {
        try {
            $this->validate();

            DB::transaction(function () {
                $letter = $this->createLetter();
                $uploadIds = $this->processFileUploads($letter);
                $this->updateLetterWithFirstUpload($letter, $uploadIds->first());
                $this->createStatusTrack($letter);
            });

            return redirect()->to('/letter');
        } catch (ModelNotFoundException $e) {
        }
    }

    protected function createLetter(): Letter
    {
        return Letter::create([
            'user_id' => Auth::id(),
            'letterable_type' => LetterUpload::class,
            'letterable_id' => 0, // Temporary value
            'title' => $this->title,
            'responsible_person' => $this->responsible_person,
            'reference_number' => $this->reference_number,
            'status' => Letter::getDefaultStateFor('status')
        ]);
    }

    protected function processFileUploads(Letter $letter): Collection
    {
        $uploads = $this->storeFiles($letter);
        LetterUpload::insert($uploads);

        return $this->getUploadIds($letter, count($uploads));
    }

    private function storeFiles(Letter $letter): array
    {
        $ordered = collect($this->files)
            ->sortKeys()
            ->values();

        return $ordered->map(function ($file, $index) use ($letter) {
            return [
                'letter_id'  => $letter->id,
                'part_name'  => 'part' . ($index + 1),
                'file_path'  => $file->store('letters', 'public'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();
    }

    private function getUploadIds(Letter $letter, int $count): Collection
    {
        return LetterUpload::where('letter_id', $letter->id)
            ->orderBy('id')
            ->take($count)
            ->pluck('id');
    }

    protected function updateLetterWithFirstUpload(Letter $letter, int $firstUploadId): void
    {
        $letter->update(['letterable_id' => $firstUploadId]);
    }

    protected function createStatusTrack(Letter $letter): void
    {
        $letter->requestStatusTrack()->create([
            'action' => (new ($letter->status)($letter))->message(),
            'created_by' => Auth::user()->name
        ]);
    }
}
