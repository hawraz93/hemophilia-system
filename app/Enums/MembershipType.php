<?php

namespace App\Enums;

enum MembershipType: string
{
    case Ordinary = 'ordinary';
    case ExecutiveBoard = 'executive_board';
    case Honorary = 'honorary';
    case Supportive = 'supportive';

    public function label(): string
    {
        return match ($this) {
            self::Ordinary => 'ئەندامی ئاسایی',
            self::ExecutiveBoard => 'ئەندامی دەستەی کارگێڕی',
            self::Honorary => 'ئەندامی فەخری',
            self::Supportive => 'ئەندامی پشتیوانی',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Ordinary => 'sky',
            self::ExecutiveBoard => 'purple',
            self::Honorary => 'amber',
            self::Supportive => 'emerald',
        };
    }
}
