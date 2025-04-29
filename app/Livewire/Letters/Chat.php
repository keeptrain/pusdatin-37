<?php

namespace App\Livewire\Letters;

use Livewire\Component;
use Livewire\Attributes\Locked;

class Chat extends Component
{

    #[Locked]
    public int $letterId;
    public function mount(int $id)
    {
        $this->letterId = $id;
    }
    public function render()
    {
        return view('livewire.letters.chat');
    }
}
