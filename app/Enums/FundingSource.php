<?php

namespace App\Enums;

/**
 * Where the money / goods for an individual aid record came from.
 * Aid paid from the association's own income is deducted from its balances.
 */
enum FundingSource: string
{
    case External = 'external';
    case Campaign = 'campaign';
    case MembershipIncome = 'membership_income';
    case GeneralIncome = 'general_income';

    public function label(): string
    {
        return match ($this) {
            self::External => 'بەخشەر / لایەنی دەرەکی (کۆمپانیا، کەسایەتی...)',
            self::Campaign => 'لە کۆگای هاوکارییە هاتووەکان',
            self::MembershipIncome => 'داهاتی ئەندامێتی',
            self::GeneralIncome => 'داهاتی گشتی کۆمەڵە',
        };
    }

    public function shortLabel(): string
    {
        return match ($this) {
            self::External => 'بەخشەری دەرەکی',
            self::Campaign => 'کۆگای هاوکاری',
            self::MembershipIncome => 'داهاتی ئەندامێتی',
            self::GeneralIncome => 'داهاتی گشتی',
        };
    }

    /** Sources a user can pick when recording aid directly for a patient. */
    public static function directOptions(): array
    {
        return [self::External, self::MembershipIncome, self::GeneralIncome];
    }
}
