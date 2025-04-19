<?php

namespace App\Livewire\Letters;

use App\Models\User;
use Livewire\Component;
use App\Models\Letters\Letter;
use Illuminate\Support\Facades\DB;
use App\Models\Letters\LetterDirect;
use App\Models\Letters\RequestStatusTrack;
use App\States\Pending;

class DirectForm extends Component
{
    public $currentStep = 1;

    public $title;

    public $body;

    public $responsible_person;

    public $reference_number;

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

    public function createLetter(int $letterableId): int
    {
        $letter = Letter::create([
            'user_id' => User::currentUser()->id,
            'title' => $this->title,
            'letterable_type' => LetterDirect::class,
            'letterable_id' => $letterableId,
            'status' => Letter::getDefaultStateFor('status'),
            'responsible_person' => $this->responsible_person,
            'reference_number' => $this->reference_number
        ]);

        return $letter->id;
    }

    public function createRequestStatusTrack(int $letterId)
    {
        return RequestStatusTrack::create([
            'letter_id' => $letterId,
            'action' => 'Request berhasil diterima.'
        ]);
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $letterableId = $this->createDirectLetter();
            $letterId = $this->createLetter($letterableId);
            $this->createRequestStatusTrack($letterId);
        });

        return redirect()->to('/letter')
            ->with('status', [
                'variant' => 'success',
                'message' => 'Create direct Letter successfully!'
            ]);
    }
}
