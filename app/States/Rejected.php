<?php

namespace App\States;

use App\States\LetterStatus;

class Rejected extends LetterStatus
{
    public function label(): string
    {
        return 'Rejected';
    }
    
    public function color(): string
    {
        return 'bg-green-500';
    }
}