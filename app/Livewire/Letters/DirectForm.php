<?php

namespace App\Livewire\Letters;

use App\Models\User;
use Livewire\Component;
use App\Models\Letters\Letter;
use Illuminate\Support\Facades\DB;
use App\Models\Letters\LetterDirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewServiceRequestNotification;

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

    public function createLetter()
    {
        $defaultStatusClass = Letter::getDefaultStateFor('status');

        return Letter::create([
            'user_id' => User::currentUser()->id,
            'title' => $this->title,
            'responsible_person' => $this->responsible_person,
            'reference_number' => $this->reference_number,
            'status' => $defaultStatusClass,
        ]);
    }

    public function createLetterDirect()
    {
        return LetterDirect::create([
            'body' => $this->body,
            // 'created_at' => now(),
            // 'updated_at' => now()
        ]);

    }

    public function createLetterMappings($letter, $letterDirect)
    {
        $letter->mapping()->create([
            'letterable_type' => LetterDirect::class,
            'letterable_id' => $letterDirect->id
        ]);
    }

    public function createStatusTrack($letter,$status)
    {
        return $letter->requestStatusTrack()->create([
            'action' => (new $status($letter))->trackingMessage(),
            'created_by' => Auth::user()->name,
        ]);
    }

    public function save()
    {
        try {
            $this->validate();

            DB::transaction(function () {
                $letter = $this->createLetter();
                $letterDirect = $this->createLetterDirect();
                $this->createLetterMappings($letter,$letterDirect);
                $this->createStatusTrack($letter, $letter->status);
                $user = User::role(['administrator','verifikator'])->get();
                Notification::send($user, new NewServiceRequestNotification($letter));
            });
            
        return redirect()->to('/letter')
            ->with('status', [
                'variant' => 'success',
                'message' => 'Create direct Letter successfully!'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
        
    }
}
