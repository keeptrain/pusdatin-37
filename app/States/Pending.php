<?php

namespace App\States;

use App\States\LetterStatus;

class Pending extends LetterStatus
{
    public function label(): string
    {
        return 'Pending';
    }
    
    public function color(): string
    {
        return 'lime';
    }

    public function message(): string
    {
        return 'Surat telah di kirim, mohon di cek berkala';
    }
}