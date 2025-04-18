<?php

namespace App\States;

use App\States\LetterStatus;

class Replied extends LetterStatus
{
    public function label(): string
    {
        return 'Replied';
    }
    
    public function color(): string
    {
        return 'bg-green-500';
    }
}