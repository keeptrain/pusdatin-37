<?php

namespace App\Enums;

enum Division: int
{
    case HEAD_ID = 2;
    case SI_ID = 3;
    case DATA_ID = 4;
    case PR_ID = 5;
    case PROMKES_ID = 6;

    public function label(): string
    {
        return match ($this) {
            self::HEAD_ID => 'Kapusdatin',
            self::SI_ID => 'Sistem Informasi',
            self::DATA_ID => 'Pengelolaan Data',
            self::PR_ID => 'Hubungan Masyarakat',
            self::PROMKES_ID => 'Promosi Kesehatan',
            default => 'Perlu disposisi'
        };
    }

    public static function getIdFromString(string $key): ?int
    {
        return match (strtolower($key)) {
            'head' => self::HEAD_ID->value,
            'si' => self::SI_ID->value,
            'data' => self::DATA_ID->value,
            'pr' => self::PR_ID->value,
            'promkes' => self::PROMKES_ID->value,
            default => null,
        };
    }

    public function getShortLabelFromId(?int $id): string
    {
        return match ($id) {
            self::HEAD_ID->value => 'kapusdatin',
            self::SI_ID->value => 'si',
            self::DATA_ID->value => 'data',
            self::PR_ID->value => 'pr',
            self::PROMKES_ID->value => 'promkes',
            default => 'Perlu disposisi'
        };
    }
}