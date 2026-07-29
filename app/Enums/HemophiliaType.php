<?php

namespace App\Enums;

enum HemophiliaType: string
{
    case HemophiliaA = 'A';
    case HemophiliaB = 'B';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::HemophiliaA => 'هیمۆفیلیا A',
            self::HemophiliaB => 'هیمۆفیلیا B',
            self::Other => 'جۆری تر',
        };
    }
}
