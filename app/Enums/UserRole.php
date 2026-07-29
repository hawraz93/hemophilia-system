<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Staff = 'staff';
    case Viewer = 'viewer';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'ئەدمین (دەسەڵاتی تەواو)',
            self::Staff => 'کارمەند (زیادکردن و نوێکردنەوە)',
            self::Viewer => 'بینەر (تەنها بینین)',
        };
    }
}
