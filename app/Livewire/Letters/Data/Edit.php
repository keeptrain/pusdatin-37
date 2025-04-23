<?php

namespace App\Livewire\Letters\Data;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Letters\Letter;
use Illuminate\Support\Facades\DB;
use App\Models\Letters\LetterUpload;
use Illuminate\Support\Facades\Auth;
use App\Models\Letters\RequestStatusTrack;
use Livewire\Attributes\Locked;

class Edit extends Component
{
    use WithFileUploads;

    #[Locked]
    public int $letterId;

    public $letter;

    public $requestStatusTrackId;

    public $title;
    public $responsible_person;
    public $reference_number;
    public $body;

    public $revisedUploads = [];

    public $revisedFiles = [];

    public function rules()
    {
        $rules = [];
        $uploads = LetterUpload::where('letter_id', $this->letterId)
            ->where('needs_revision', true)
            ->get();

        foreach ($uploads as $upload) {
            $rules["revisedFiles.{$upload->part_name}"] = [
                'required',
                'file',
                'mimes:pdf,docx,doc', 
                'max:2048', 
            ];
        }
        return $rules;
    }

    public function mount(int $id)
    {
        $this->letterId = $id;
        $this->letter = Letter::with('uploads')->findOrFail($id);
        $this->title = $this->letter->title;
        $this->responsible_person =  $this->letter->responsible_person;
        $this->body = $this->letter->body;

        $this->revisedUploads = $this->letter->uploads->where('needs_revision', true);
    }

    public function render()
    {
        return view('livewire.letters.data.edit');
    }

    public function updateForRevision() {}

    public function save()
    {
        $this->validate();
        DB::transaction(function () {
            $letter = Letter::with('uploads')->findOrFail($this->letterId);

            $letter->update([
                'title' => $this->title,
                'current_revision' => $letter->current_revision + 1,
                'active_revision' => false,
            ]);

            $uploads = LetterUpload::where('letter_id', $letter->id)
                ->where('needs_revision', true)
                ->get();

            $revisedParts = [];
            $nameUser = Auth::user()->name;

            foreach ($uploads as $upload) {
                $partName = $upload->part_name;

                if (!isset($this->revisedFiles[$partName])) {
                    continue;
                }

                $newFile = $this->revisedFiles[$partName];
                $newPath = $newFile->store('letters', 'public');

                $upload->update([
                    'file_path' => $newPath,
                    'version' => $upload->version + 1,
                    'needs_revision' => false,
                    'revision_note' => null,
                    'updated_at' => now(),
                ]);

                $revisedParts[] = ucfirst($partName);
            }

            if (!empty($revisedParts)) {
                RequestStatusTrack::create([
                    'letter_id' => $this->letterId,
                    'action' => $nameUser . ' telah melakukan revisi di bagian: ' . implode(', ', $revisedParts),
                    'created_by' => $nameUser,
                ]);
            }
        });
    }
}
