<?php

namespace App\Livewire\Letters\Data;

use Livewire\Component;

class TrackingProgres extends Component
{
    public string $status;

    public function getProgressWidthProperty(): int
    {
        return match (strtolower($this->status)) {
            'pending'                => 5,
            'disposisi'              => 23,
            'proses'                 => 42,
            'replied'                => 60,
            'approved by kasatpel'   => 77,
            'approved by kapusdatin' => 95,
            default                   => 0,
        };
    }

    public function render()
    {
        return view('components.user.tracking-progres');
    }
}
