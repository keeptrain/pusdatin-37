<?php

namespace App\Livewire\Letters;

use App\Models\User;
use Livewire\Component;
use App\Models\Letters\Letter;
use App\Models\Letters\LetterDirect;

class DirectForm extends Component
{
    public $currentStep = 1;

    public $body;

    public $responsible_person;

    public $reference_number;

    public function validateInput()
    {
        $this->validate([
            'body' => 'required',
            'responsible_person' => 'required|string',
            'reference_number' => 'required|string',
        ], [
            'body.required' => 'Body is required',
            'responsible_person.required' => 'Responsible person is required',
            'reference_number.required' => 'Reference number is required'
        ]);
    }

    public function createDirectLetter(): LetterDirect
    {
        return LetterDirect::create([
            'body' => $this->body
        ]);
    }

    public function createLetter(): Letter
    {
        return Letter::create([
            'user_id' => User::currentUser()->id,
            'category_type' => LetterDirect::class,
            'category_id' => $this->createDirectLetter()->id,
            'status' => 'Read',
            'responsible_person' => $this->responsible_person,
            'reference_number' => $this->reference_number
        ]);
    }

    public function save()
    {
        $this->validateInput();

        $this->createDirectLetter();

        $this->createLetter();

        return redirect()->to('/letter')
            ->with('status', [
                'variant' => 'success',
                'message' => 'Create direct Letter successfully!'
            ]);
    }
}
