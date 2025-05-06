<?php

namespace App\Livewire\Letters;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\Auth;
use App\Models\Letters\LetterMessage;

class Chat extends Component
{

    #[Locked]
    public int $letterId;
    public $body;
    public $messages;

    public function mount(int $id)
    {
        $this->letterId = $id;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = LetterMessage::where('letter_id', $this->letterId)
        ->with(['sender', 'receiver'])
        ->orderBy('created_at')
        ->get();
    }
    
    public function send()
    {
        LetterMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->getReceiverId(),
            'letter_id' => $this->letterId,
            'body' => $this->body,
        ]);

        $this->body = '';
        $this->loadMessages();
    }

    protected function getReceiverId(): int
    {
        return User::where('id', '!=', Auth::id())->first()?->id ?? Auth::id();
    }

}
