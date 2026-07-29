<?php

namespace App\Enums;

enum PatientListStatus: string
{
    case Green = 'green';
    case Yellow = 'yellow';
    case Red = 'red';

    public function label(): string
    {
        return match ($this) {
            self::Green => 'لیستی سەوز (تەواو و بەردەوام)',
            self::Yellow => 'لیستی زەرد (داتای ناتەواو)',
            self::Red => 'لیستی سوور (بێوەڵام / پەیوەندی پچڕاو)',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Green => 'positive',
            self::Yellow => 'warning',
            self::Red => 'negative',
        };
    }
}
