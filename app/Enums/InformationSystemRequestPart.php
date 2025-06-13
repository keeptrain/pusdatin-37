<?php

namespace App\Enums;

enum InformationSystemRequestPart: int
{
    case NOTA_DINAS = 0;
    case DOCUMENT_INDENTIFIKASI_APLIKASI = 1;
    case DOCUMENT_SOP_APLIKASI = 2;
    case DOCUMENT_PAKTA_INTEGRITAS_IMPLEMENTASI = 3;
    case FORM_RFC = 4;
    case NON_DISCLOSURE_AGREEMENT = 5;

    public function label(): string
    {
        return match ($this) {
            self::NOTA_DINAS => 'Nota Dinas',
            self::DOCUMENT_INDENTIFIKASI_APLIKASI => 'Dokumen Identifikasi Aplikasi ',
            self::DOCUMENT_SOP_APLIKASI => 'SOP Aplikasi',
            self::DOCUMENT_PAKTA_INTEGRITAS_IMPLEMENTASI => 'Pakta Integritas Implementasi',
            self::FORM_RFC => 'Form RFC Pusdatinkes',
            self::NON_DISCLOSURE_AGREEMENT => 'Surat Perjanjian Kerasahasiaan',
        };
    }

    public function shortLabel(): string
    {
        return match ($this) {
            self::NOTA_DINAS => 'Nodin',
            self::DOCUMENT_INDENTIFIKASI_APLIKASI => 'SPBE',
            self::DOCUMENT_SOP_APLIKASI => 'SOP',
            self::DOCUMENT_PAKTA_INTEGRITAS_IMPLEMENTASI => 'Pemanfaatan Aplikasi',
            self::FORM_RFC => 'Form RFC',
            self::NON_DISCLOSURE_AGREEMENT => 'NDA',
        };
    }
}
