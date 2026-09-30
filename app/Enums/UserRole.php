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

    /**
     * Higher rank means more privileges.
     */
    public function rank(): int
    {
        return match ($this) {
            self::SuperAdmin => 5,
            self::OrgHead => 4,
            self::Admin => 3,
            self::Staff => 2,
            self::Viewer => 1,
        };
    }

    /**
     * Roles that a user with the given role is allowed to assign.
     *
     * @return array<self>
     */
    public static function assignableBy(self $role): array
    {
        return array_values(array_filter(self::cases(), fn (self $r) => $r->rank() <= $role->rank()));
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
