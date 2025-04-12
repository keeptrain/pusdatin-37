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

    public function nameFile(): string {
        $user = Auth::user();
        $date = Carbon::now()->format('d');
        $fileName = Str::slug($user->name) . '-' . $date . '.pdf';

        return $fileName;
    }

    public function save()
    {
        $this->validateInput();

        $upload = LetterUpload::create([
            'file_name' => $this->nameFile(),
            'file_path' => 'letters/' . $this->file->getClientOriginalName()
        ]);

        Letter::create([
            'user_id' => User::currentUser()->id,
            'category_type' => LetterUpload::class,
            'category_id' => $upload->id,
            'responsible_person' => $this->responsible_person,
            'reference_number' => $this->reference_number
        ]);

        
        return redirect()->to('/letter')
            ->with('status', [
                'variant' => 'success',
                'message' => 'Uploaded Letter successfully!'
            ]);
    }
}
