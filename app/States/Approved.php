<?php

namespace App\States;

use App\States\LetterStatus;

class Approved extends LetterStatus
{
    public function label(): string
    {
        return 'Approved';
    }
    
    public function color(): string
    {
        return 'bg-green-500';
    }

    public function message(): string
    {
        return 'Surat disetujui';
    }
}