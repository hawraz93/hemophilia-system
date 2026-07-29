<?php

namespace App\Enums;

enum MaritalStatus: string
{
    case Single = 'single';
    case Married = 'married';
    case Divorced = 'divorced';
    case Widowed = 'widowed';

    public function label(): string
    {
        return match ($this) {
            self::Single => 'سەڵت',
            self::Married => 'هاوسەردار',
            self::Divorced => 'جیابووەوە',
            self::Widowed => 'بێوەژین / بێوەپیاو',
        };
    }
}
