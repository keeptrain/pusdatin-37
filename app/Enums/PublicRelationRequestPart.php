<?php

namespace App\Enums;

enum PublicRelationRequestPart: int
{
    case NOTA_DINAS = 0;
    case AUDIO = 1;
    case INFOGRAFIS = 2;
    case POSTER = 3;
    case MEDIA = 4;
    case BUMPER = 5;
    case BACKDROP_KEGIATAN = 6;
    case SPANDUK = 7;
    case ROLL_BANNER = 8;
    case SERTIFIKAT = 9;
    case PRESS_RELEASE = 10;
    case ARTIKEL = 11;
    case PELIPUTAN = 12;

    public function label(): string
    {
        return match ($this) {
            self::NOTA_DINAS => 'Nota Dinas',
            self::AUDIO => 'Audio',
            self::INFOGRAFIS => 'Infografis',
            self::POSTER => 'Poster',
            self::MEDIA => 'Media',
            self::BUMPER => 'Bumper',
            self::BACKDROP_KEGIATAN => 'Backdrop Kegiatan',
            self::SPANDUK => 'Spanduk',
            self::ROLL_BANNER => 'Roll Banner',
            self::SERTIFIKAT => 'Sertifikat',
            self::PRESS_RELEASE => 'Press Release',
            self::ARTIKEL => 'Artikel',
            self::PELIPUTAN => 'Peliputan'
        };
    }
}
