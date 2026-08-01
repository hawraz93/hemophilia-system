<?php

namespace App\Enums;

enum HemophiliaType: string
{
    case HemophiliaA = 'A';
    case HemophiliaB = 'B';
    case VonWillebrand = 'von_willebrand';
    case OtherBleeding = 'other_bleeding';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::HemophiliaA => 'هیمۆفیلیای A',
            self::HemophiliaB => 'هیمۆفیلیای B',
            self::VonWillebrand => 'ڤۆن ویلی براند',
            self::OtherBleeding => 'خوێنبەربوونی تر',
            self::Other => 'جۆری تر',
        };
    }
}
