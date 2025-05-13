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

    public function toastMessage(): string
    {
        return "Letter successfully update status to replied!";
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
    public function icon(): string
    {
        return 'send-horizontal';
    }
    public function badgeBg(): string
    {
        return 'bg-orange-500';
    }
    public function percentage(): string
    {
        return '70%';
    }
    public function percentageBar(): string
    {
        return 'w-[70%]';
    }
}
