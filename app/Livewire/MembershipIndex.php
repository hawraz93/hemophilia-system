<?php

namespace App\Livewire;

use App\Enums\FundingSource;
use App\Models\Assistance;
use App\Models\Membership;
use App\Models\MembershipPayment;
use App\Services\FinanceSummary;
use Livewire\Component;
use Livewire\WithPagination;

class MembershipIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function render()
    {
        $query = MembershipPayment::with(['patient', 'membership']);

        if ($this->search) {
            $s = '%'.$this->search.'%';
            $query->whereHas('patient', function ($q) use ($s) {
                $q->where('full_name', 'like', $s)
                  ->orWhere('membership_number', 'like', $s)
                  ->orWhere('patient_code', 'like', $s);
            });
        }

        $payments = $query->latest()->paginate(15);
        $totalCollected = (clone $query)->sum('amount_paid');
        $finance = FinanceSummary::get();

        // Aid paid out of the association's own income
        $internalSpending = Assistance::with('patient')
            ->whereIn('funding_source', [FundingSource::MembershipIncome, FundingSource::GeneralIncome])
            ->latest('assistance_date')
            ->take(10)
            ->get();

        return view('livewire.membership-index', compact('payments', 'totalCollected', 'finance', 'internalSpending'));
    }
}
