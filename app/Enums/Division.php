<?php

namespace App\Enums;

enum Division: int
{
    case ADMIN_ID = 1;
    case HEAD_ID = 2;
    case SI_ID = 3;
    case DATA_ID = 4;
    case PR_ID = 5;
    case PROMKES_ID = 6;
    case USER_ID = 7;

    public function label(): string
    {
        return match ($this) {
            self::ADMIN_ID => 'Administrator',
            self::HEAD_ID => 'Kapusdatin',
            self::SI_ID => 'Sistem Informasi',
            self::DATA_ID => 'Pengelolaan Data',
            self::PR_ID => 'Hubungan Masyarakat',
            self::PROMKES_ID => 'Promosi Kesehatan',
            self::USER_ID => 'User',
            default => 'Perlu disposisi'
        };
    }

    public static function getIdFromString(string $key): ?int
    {
        return match (strtolower($key)) {
            'admin' => self::ADMIN_ID->value,
            'head' => self::HEAD_ID->value,
            'si' => self::SI_ID->value,
            'data' => self::DATA_ID->value,
            'pr' => self::PR_ID->value,
            'promkes' => self::PROMKES_ID->value,
            'user' => self::USER_ID->value,
            default => null,
        };
    }

    public function getShortLabelFromId(?int $id): string
    {
        return match ($id) {
            self::ADMIN_ID->value => 'admin',
            self::HEAD_ID->value => 'kapusdatin',
            self::SI_ID->value => 'si',
            self::DATA_ID->value => 'data',
            self::PR_ID->value => 'pr',
            self::PROMKES_ID->value => 'promkes',
            self::USER_ID->value => 'user',
            default => 'Perlu disposisi'
        };
    }

    public function getRoleLabelFromId(): string
    {
        $id = $this->value;
        return match ($id) {
            self::ADMIN_ID->value => 'Administrator',
            self::HEAD_ID->value => 'Kapusdatin',
            self::SI_ID->value => 'SI Verifikator',
            self::DATA_ID->value => 'Data Verifikator',
            self::PR_ID->value => 'PR Verifikator',
            self::PROMKES_ID->value => 'Promkes Verifikator',
            self::USER_ID->value => 'User',
            default => 'Perlu disposisi'
        };
    }
}