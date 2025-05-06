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

    public function trackingMessage(): string
    {
        return 'Surat anda mendapatkan balasan, harap di periksa.';
    }

    public function userNotificationMessage(array $context): string
    {
        if (isset($context['verifikator_role']) && in_array($context['verifikator_role'], ['administrator', 'verifikator'])) {
            return 'Permohonan layanan anda mendapatkan balasan';
        }
    
        return 'Permohonan layanan ini direvisi oleh pemohon';
    }
}