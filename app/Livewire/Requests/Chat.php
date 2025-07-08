<?php

namespace App\Livewire\Requests;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\Auth;
use App\Models\RequestMessage;

class Chat extends Component
{
    #[Locked]
    public int $requestId;
    public $body;
    public $messages;

    public function mount(int $id)
    {
        $this->requestId = $id;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = RequestMessage::where('request_id', $this->requestId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at')
            ->get();
    }

    public function send()
    {
        RequestMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->getReceiverId(),
            'request_id' => $this->requestId,
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