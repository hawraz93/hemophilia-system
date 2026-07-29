<?php

namespace App\Livewire;

use App\Enums\HemophiliaType;
use App\Enums\PatientListStatus;
use App\Models\Assistance;
use App\Models\MembershipPayment;
use App\Models\Patient;
use Livewire\Component;

class ReportIndex extends Component
{
    public string $reportType = 'all_patients';

    public function render()
    {
        $data = collect();

        if ($this->reportType === 'all_patients') {
            $data = Patient::latest()->get();
        } elseif ($this->reportType === 'hemophilia_a') {
            $data = Patient::where('hemophilia_type', HemophiliaType::HemophiliaA)->get();
        } elseif ($this->reportType === 'hemophilia_b') {
            $data = Patient::where('hemophilia_type', HemophiliaType::HemophiliaB)->get();
        } elseif ($this->reportType === 'red_list') {
            $data = Patient::where('list_status', PatientListStatus::Red)->get();
        } elseif ($this->reportType === 'incomplete_data') {
            $data = Patient::where('list_status', PatientListStatus::Yellow)->get();
        } elseif ($this->reportType === 'assistance_summary') {
            $data = Assistance::with('patient')->latest()->get();
        } elseif ($this->reportType === 'membership_summary') {
            $data = MembershipPayment::with('patient')->latest()->get();
        }

        return view('livewire.report-index', compact('data'));
    }
}
