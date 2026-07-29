<?php

namespace App\Livewire;

use App\Models\Membership;
use App\Models\MembershipPayment;
use App\Models\Patient;
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

        return view('livewire.membership-index', compact('payments', 'totalCollected'));
    }
}
