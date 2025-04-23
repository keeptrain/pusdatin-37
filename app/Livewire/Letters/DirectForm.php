<?php

namespace App\Livewire\Letters;

use App\Models\User;
use Livewire\Component;
use App\Models\Letters\Letter;
use Illuminate\Support\Facades\DB;
use App\Models\Letters\LetterDirect;
use Illuminate\Support\Facades\Auth;

class DirectForm extends Component
{
    public $title = '';

    public $responsible_person = '';

    public $reference_number = '';

    public $body = '';

    public function rules()
    {
        return [
            'body' => 'required',
            'title' => 'required|string|max:255',
            'responsible_person' => 'required|string',
            'reference_number' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'body.required' => 'Body is required',
            'responsible_person.required' => 'Responsible person is required',
            'reference_number.required' => 'Reference number is required'
        ];
    }

    public function createDirectLetter(): int
    {
        $data = LetterDirect::create([
            'body' => $this->body
        ]);

        return $data->id;
    }

    public function createLetter(int $letterableId)
    {
        $defaultStatusClass = Letter::getDefaultStateFor('status');

        $letter = Letter::create([
            'user_id' => User::currentUser()->id,
            'letterable_type' => LetterDirect::class,
            'letterable_id' => $letterableId,
            'title' => $this->title,
            'responsible_person' => $this->responsible_person,
            'reference_number' => $this->reference_number,
            'status' => $defaultStatusClass,
        ]);

        $letter->requestStatusTrack()->create([
            'action' => (new $defaultStatusClass($letter))->message(),
            'created_by' => Auth::user()->name,
        ]);
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $letterableId = $this->createDirectLetter();
            $this->authorize('create', $letterableId); 
            $this->createLetter($letterableId);
        });

        return redirect()->to('/letter')
            ->with('status', [
                'variant' => 'success',
                'message' => 'Create direct Letter successfully!'
            ]);
    }
}
