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
        return 'yellow';
    }

    public function message(): string
    {
        return 'Surat anda mendapatkan balasan, harap di periksa.';
    }
}