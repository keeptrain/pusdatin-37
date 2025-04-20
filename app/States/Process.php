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
        return 'sky';
    }

    public function message(): string
    {
        return 'Surat sedang dalam proses.';
    }
}