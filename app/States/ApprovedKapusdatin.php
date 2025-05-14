<?php

namespace App\States;

use App\States\LetterStatus;

class ApprovedKapusdatin extends LetterStatus
{
    public function label(): string
    {
        return 'Approved by Kapusdatin';
    }
    
    public function color(): string
    {
        return 'green';
    }

    public function toastMessage(): string
    {
        return 'Letter successfully update status to approved!';
    }

    public function trackingMessage($division): string
    {
        return 'Permohonan layanan anda telah disetujui oleh Kepala Pusat Data dan Teknologi Dinas Kesehatan' ;
    }

    public function userNotificationMessage(array $context): string
    {
        return "Permohonan layanan anda diterima";
    }
}