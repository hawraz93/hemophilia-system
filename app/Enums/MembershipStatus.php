<?php

namespace App\Enums;

enum MembershipStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Suspended = 'suspended';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'چالاک',
            self::Inactive => 'ناچالاک',
            self::Suspended => 'ڕاگرابوو',
        };
    }
}
