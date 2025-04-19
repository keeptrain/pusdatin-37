<?php

namespace App\Livewire\Letters\Data;

use Livewire\Component;
use App\Models\Letters\Letter;

class Edit extends Component
{
    public int $letterId;
    public $letter;

    public function mount(int $id)
    {
        $this->letterId = $id;
        
        $this->letter = Letter::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.letters.data.edit');
    }

    
}
