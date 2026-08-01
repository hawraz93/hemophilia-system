<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case OrgHead = 'org_head';
    case Admin = 'admin';
    case Staff = 'staff';
    case Viewer = 'viewer';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'بەرپرسی سەرجەم ڕێکخراوەکان',
            self::OrgHead => 'سەرۆکی ڕێکخراو',
            self::Admin => 'ئەدمێن',
            self::Staff => 'کارمەندی ڕێکخراو',
            self::Viewer => 'بینەر و خوێنەر',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::SuperAdmin => 'purple',
            self::OrgHead => 'indigo',
            self::Admin => 'rose',
            self::Staff => 'blue',
            self::Viewer => 'slate',
        };
    }
}
