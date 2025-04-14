<?php

namespace App\Livewire\Letters;

use App\Models\Letters\Letter;
use App\Models\Letters\LetterUpload;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * [Description Upload]
 */
class UploadForm extends Component
{
    use WithFileUploads;

    public $file;

    public $responsible_person;

    public $reference_number;

    public function validateInput()
    {
        $this->validate([
            'file' => 'required|mimes:pdf|max:1024',
            'responsible_person' => 'required|string',
            'reference_number' => 'required|string'
        ]);
    }

    public function nameFile(): string
    {
        $user = Auth::user();
        $dateTime = Carbon::now()->format('YmdHis');
        $fileName = Str::slug($user->name) . '-' . $dateTime . '.pdf';

        return $fileName;
    }

    public function createUploadLetter(): LetterUpload
    {
        return LetterUpload::create([
            'file_name' => $this->nameFile(),
            'file_path' => $this->file->storeAs('letters', $this->nameFile(), 'public')
        ]);
    }

    public function createLetter(): Letter
    {
        return Letter::create([
            'user_id' => User::currentUser()->id,
            'letterable_type' => LetterUpload::class,
            'letterable_id' => $this->createUploadLetter()->id,
            'status' => 'New',
            'responsible_person' => $this->responsible_person,
            'reference_number' => $this->reference_number
        ]);
    }

    public function save()
    {
        $this->validateInput();

        $this->createUploadLetter();

        $this->createLetter();

        return redirect()->to('/letter')
            ->with('status', [
                'variant' => 'success',
                'message' => 'Uploaded Letter successfully!'
            ]);
    }
}
