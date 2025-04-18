<?php

namespace App\States;

use App\States\LetterStatus;

class Process extends LetterStatus
{
    public function label(): string
    {
        return 'Process';
    }
    
    public function color(): string
    {
        return 'bg-green-500';
    }
}