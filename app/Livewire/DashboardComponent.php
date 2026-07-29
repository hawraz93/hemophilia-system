<?php

namespace App\Livewire;

use App\Enums\HemophiliaType;
use App\Enums\PatientListStatus;
use App\Models\Assistance;
use App\Models\Patient;
use App\Models\PatientContact;
use Carbon\Carbon;
use Livewire\Component;

class DashboardComponent extends Component
{
    public function render()
    {
        $totalPatients = Patient::count();
        $hemophiliaACount = Patient::where('hemophilia_type', HemophiliaType::HemophiliaA)->count();
        $hemophiliaBCount = Patient::where('hemophilia_type', HemophiliaType::HemophiliaB)->count();

        $eighteenYearsAgo = Carbon::now()->subYears(18)->format('Y-m-d');
        $childrenCount = Patient::where('dob', '>=', $eighteenYearsAgo)
            ->orWhere('age', '<', 18)
            ->count();

        $adultsCount = $totalPatients - $childrenCount;

        $greenListCount = Patient::where('list_status', PatientListStatus::Green)->count();
        $yellowListCount = Patient::where('list_status', PatientListStatus::Yellow)->count();
        $redListCount = Patient::where('list_status', PatientListStatus::Red)->count();

        $totalAidCount = Assistance::count();
        $totalAidAmount = Assistance::sum('amount');

        $recentPatients = Patient::latest()->take(5)->get();
        $recentAssistances = Assistance::with('patient')->latest()->take(5)->get();
        $recentContacts = PatientContact::with('patient')->latest()->take(5)->get();

        return view('livewire.dashboard-component', compact(
            'totalPatients',
            'hemophiliaACount',
            'hemophiliaBCount',
            'childrenCount',
            'adultsCount',
            'greenListCount',
            'yellowListCount',
            'redListCount',
            'totalAidCount',
            'totalAidAmount',
            'recentPatients',
            'recentAssistances',
            'recentContacts'
        ));
    }
}
