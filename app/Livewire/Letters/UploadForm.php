<?php

namespace App\Livewire\Letters;

use Carbon\Carbon;
use App\Models\User;
use App\States\Pending;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Models\Letters\Letter;
use Illuminate\Support\Facades\DB;
use App\Models\Letters\LetterUpload;
use Illuminate\Support\Facades\Auth;

/**
 * [Description Upload]
 */
class UploadForm extends Component
{
    use WithFileUploads;

    public $file;

    public bool $fileReady = false;

    public $title = '';

    public $responsible_person = '';

    public $reference_number = '';

    public function rules()
    {
        return [
            'file' => 'required|mimes:pdf|max:1024',
            'title' => 'required|string|max:255',
            'responsible_person' => 'required|string',
            'reference_number' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'File is required',
            'file.mimes' =>  'File wrong extension.',
            'title.required' => 'Title is required',
            'responsible_person.required' => 'Responsible person is required',
            'reference_number.required' => 'Reference number is required'
        ];
    }

    public function updatedFile($value)
    {
        $this->fileReady = !is_null($value);
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

    public function createUploadLetter(): LetterUpload
    {
        return LetterUpload::create([
            'file_name' => $this->nameFile(),
            'file_path' => $this->file->storeAs('letters', $this->pathFile(), 'public')
        ]);
    }

    public function createLetter(): Letter
    {
        return Letter::create([
            'user_id' => User::currentUser()->id,
            'title' => $this->title,
            'letterable_type' => LetterUpload::class,
            'letterable_id' => $this->createUploadLetter()->id,
            'status' => Pending::class,
            'responsible_person' => $this->responsible_person,
            'reference_number' => $this->reference_number
        ]);
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $this->createUploadLetter();
            $this->createLetter();
        });

        return redirect()->to('/letter')
            ->with('status', [
                'variant' => 'success',
                'message' => 'Uploaded Letter successfully!'
            ]);
    }
}
