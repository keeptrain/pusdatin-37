<?php

namespace App\Livewire\Letters;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Template;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Models\Letters\Letter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
// use App\Models\Letters\LetterUpload;
use Illuminate\Support\Facades\Auth;
use App\Models\letters\LettersMapping;
use App\Models\Documents\UploadVersion;
use App\Models\Documents\DocumentUpload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewServiceRequestNotification;

class UploadForm extends Component
{
    use WithFileUploads;

    public $title = '';

    public $reference_number = '';

    public $files = [];

    public bool $fileReady = false;

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            // 'responsible_person' => 'required|string',
            'reference_number' => 'required|string',
            'files.0' => 'required|file|mimes:pdf|max:1048',
            'files.1' => 'required|file|mimes:pdf|max:1048',
            'files.2' => 'file|mimes:pdf|max:1048'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Title is required',
            // 'responsible_person.required' => 'Responsible person is required',
            'reference_number.required' => 'Reference number is required',
            'files.0.required' => 'Nota dinas harus ada',
            'files.1.required' => 'SOP harus ada',
        ];
    }

    public function mount() {}

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $letter = $this->createLetter();
            $uploads = $this->storeFiles();
            $uploadIds = $this->insertLetterUploads($uploads);
            $this->createLetterMappings($letter->id, $uploadIds);
            $this->createStatusTrack($letter);

            $user = User::role('head_verifier')->get();
            Notification::sendNow($user, new NewServiceRequestNotification($letter));

            return redirect()->to('letter/history')
                ->with('status', [
                    'variant' => 'success',
                    'message' => 'Create direct Letter successfully!'
                ]);
        });
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

    public function createLetter()
    {
        return Letter::create([
            'user_id' => auth()->user()->id,
            'title' => $this->title,
            'responsible_person' => auth()->user()->name,
            'reference_number' => $this->reference_number,
            'active_checking' => 2,
        ]);
    }

    public function storeFiles(): array
    {
        return collect($this->files)
            ->sortKeys()
            ->values()
            ->map(function ($file, $index) {
                return [
                    'part_number' => $index + 1,
                    'file_path' => $file->store('documents', 'public'),
                ];
            })->toArray();
    }

    protected function insertLetterUploads(array $uploads)
    {

        $documentVersionId = collect();
        foreach ($uploads as $upload) {
            $documentUpload = DocumentUpload::create([
                'part_number' => $upload['part_number']
            ]);

            $version = UploadVersion::create([
                'document_upload_id' => $documentUpload->id,
                'file_path' => $upload['file_path'],
                'is_resolved' => true,
            ]);

            // $this->createDocumentUploadVersion($documentUpload,$upload['file_path']);

            $documentUpload->update([
                'document_upload_version_id' => $version->id,
            ]);

            $documentVersionId->push($documentUpload->id);
        }

        return $documentVersionId;
    }

    protected function createLetterMappings(int $letterId, Collection $uploadIds): void
    {
        $mappings = $uploadIds->map(function ($uploadId) use ($letterId) {
            return [
                'letter_id' => $letterId,
                'letterable_type' => DocumentUpload::class,
                'letterable_id' => $uploadId,
            ];
        })->toArray();

        LettersMapping::insert($mappings);
    }

    protected function createStatusTrack(Letter $letter): void
    {
        $letter->requestStatusTrack()->create([
            'action' => $letter->status->trackingMessage(null),
            'created_by' => auth()->user()->name
        ]);
    }

    public function downloadTemplate($typeNumber)
    {
        $template = Template::where('part_number', $typeNumber)->where('is_active', '1')->first();

        if ($template) {
            $filePath = $template->file_path;

            $fileDownload = Storage::disk('public')->path($filePath);

            return response()->download($fileDownload);
        }

        abort(404, 'Template not found.');
    }
}
