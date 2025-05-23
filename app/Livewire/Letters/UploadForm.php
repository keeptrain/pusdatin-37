<?php

namespace App\Livewire\Letters;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Models\Letters\Letter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Letters\LetterUpload;
use Illuminate\Support\Facades\Auth;
use App\Models\letters\LettersMapping;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewServiceRequestNotification;
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
            'files.0.required' => 'Files 1 required',
            'files.1.required' => 'Files 2 required',
            'files.2.required' => 'Files 3 required',
        ];
    }

    public function mount() {}

    public function save()
    {
        try {
            $this->validate();

            DB::transaction(function () {
                $letter = $this->createLetter();
                $uploads = $this->storeFiles();
                $uploadIds = $this->insertLetterUploads($uploads);
                $this->createLetterMappings($letter->id, $uploadIds);
                $this->createStatusTrack($letter);
                $user = User::role(['administrator','verifikator'])->get();
                Notification::send($user, new NewServiceRequestNotification($letter));
            });

            return redirect()->to('/letter')
                ->with('status', [
                    'variant' => 'success',
                    'message' => 'Create direct Letter successfully!'
                ]);
        } catch (ModelNotFoundException $e) {
            // Handle error
        }
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

    protected function createLetter(): Letter
    {
        return Letter::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'responsible_person' => $this->responsible_person,
            'reference_number' => $this->reference_number,
            'status' => Letter::getDefaultStateFor('status'),
        ]);
    }

    protected function storeFiles(): array
    {
        return collect($this->files)
            ->sortKeys()
            ->values()
            ->map(function ($file, $index) {
                return [
                    'part_name' => 'part' . ($index + 1),
                    'file_path' => $file->store('letters', 'public'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();
    }

    protected function insertLetterUploads(array $uploads): Collection
    {
        LetterUpload::insert($uploads);

        return LetterUpload::latest('id')
            ->take(count($uploads))
            ->pluck('id');
    }

    protected function createLetterMappings(int $letterId, Collection $uploadIds): void
    {
        $mappings = $uploadIds->map(function ($uploadId) use ($letterId) {
            return [
                'letter_id' => $letterId,
                'letterable_type' => LetterUpload::class,
                'letterable_id' => $uploadId,
            ];
        })->toArray();

        LettersMapping::insert($mappings);
    }

    protected function createStatusTrack(Letter $letter): void
    {
        $letter->requestStatusTrack()->create([
            'action' => (new ($letter->status)($letter))->message(),
            'created_by' => Auth::user()->name
        ]);
    }
}
