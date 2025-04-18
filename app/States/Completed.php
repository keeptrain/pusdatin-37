<?php

namespace App\States;

use App\States\LetterStatus;

class Completed extends LetterStatus
{
    public function label(): string
    {
        return 'Completed';
    }
    
    public function color(): string
    {
        return 'bg-green-500';
    }
}