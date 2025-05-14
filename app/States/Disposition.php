<?php

namespace App\States;

use App\States\LetterStatus;

class Disposition extends LetterStatus
{
    public function label(): string
    {
        return 'Disposition';
    }
    
    public function color(): string
    {
        return 'lime';
    }

    public function toastMessage(): string
    {
        return 'The letter was created successfully';
    }

    public function trackingMessage(?int $division): string
    {
        $divisionName = $this->getDivisionName($division);
        
        return "Permohonan layanan anda di disposisikan oleh Kepala Pusat Data dan Teknologi Dinas Kesehatan 
                ke Kepala Satuan Pelaksana {$divisionName}";
    }

    public function userNotificationMessage(array $context): string
    {
        return "Surat baru telah diajukan oleh " . $context['responsible_person'];
    }
}