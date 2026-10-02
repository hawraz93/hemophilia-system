<?php

namespace App\Services;

use App\Enums\FundingSource;
use App\Models\Assistance;
use App\Models\MembershipPayment;

/**
 * Balances of the association's own funds.
 *
 * Membership fees are the income the system records. Financial aid paid from
 * membership income is deducted from both the membership balance and the
 * general balance; aid paid from general income is deducted from the general
 * balance only. Aid funded by outside donors or received stock is not deducted.
 */
class FinanceSummary
{
    public static function get(): array
    {
        $membershipIncome = (int) MembershipPayment::sum('amount_paid');
        $spentFromMembership = (int) Assistance::where('funding_source', FundingSource::MembershipIncome)->sum('amount');
        $spentFromGeneral = (int) Assistance::where('funding_source', FundingSource::GeneralIncome)->sum('amount');

        return [
            'membership_income' => $membershipIncome,
            'spent_from_membership' => $spentFromMembership,
            'membership_balance' => $membershipIncome - $spentFromMembership,
            'spent_from_general' => $spentFromGeneral,
            'general_balance' => $membershipIncome - $spentFromMembership - $spentFromGeneral,
            'exempt_members' => MembershipPayment::where('is_exempt', true)->distinct('patient_id')->count('patient_id'),
        ];
    }

    /**
     * Error message if membership income cannot cover the amount, otherwise null.
     * General income may include money the system does not record, so it is not capped.
     */
    public static function insufficientFundsError(?FundingSource $source, int $amount): ?string
    {
        if ($source !== FundingSource::MembershipIncome || $amount <= 0) {
            return null;
        }

        $balance = self::get()['membership_balance'];

        return $amount > $balance
            ? 'باڵانسی داهاتی ئەندامێتی بەس نییە. ماوە: ' . number_format($balance) . ' IQD'
            : null;
    }
}
