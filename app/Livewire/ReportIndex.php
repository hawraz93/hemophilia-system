<?php

namespace App\Livewire;

use App\Enums\HemophiliaType;
use App\Enums\PatientListStatus;
use App\Models\Assistance;
use App\Models\MembershipPayment;
use App\Models\Patient;
use App\Services\ExcelExporter;
use Livewire\Component;

class ReportIndex extends Component
{
    public string $reportType = 'all_patients';

    // Dynamic Column Selector for Patient Reports
    public array $selectedColumns = [
        'patient_code' => true,
        'full_name' => true,
        'hemophilia_type' => true,
        'membership_type' => true,
        'phone' => true,
        'party_affiliation' => true,
        'voting_card_number' => true,
        'national_id' => true,
        'address' => true,
        'dob' => true,
        'list_status' => true,
    ];

    public function toggleColumn(string $col)
    {
        if (isset($this->selectedColumns[$col])) {
            $this->selectedColumns[$col] = !$this->selectedColumns[$col];
        }
    }

    public function selectAllColumns()
    {
        foreach ($this->selectedColumns as $k => $v) {
            $this->selectedColumns[$k] = true;
        }
    }

    public function deselectAllColumns()
    {
        foreach ($this->selectedColumns as $k => $v) {
            $this->selectedColumns[$k] = false;
        }
        $this->selectedColumns['full_name'] = true;
    }

    public function exportExcel()
    {
        if (in_array($this->reportType, ['assistance_summary'])) {
            $data = Assistance::with('patient')->latest()->get();
            $headers = ['ژ. هاوکاری', 'ناوی نەخۆش', 'بەروار', 'جۆری هاوکاری', 'سەرچاوە', 'بڕی پارە (IQD)'];
            $rows = [];
            foreach ($data as $aid) {
                $rows[] = [
                    $aid->assistance_number,
                    $aid->patient?->full_name ?? '—',
                    $aid->assistance_date->format('Y-m-d'),
                    $aid->category->label(),
                    $aid->source_funder ?? '—',
                    $aid->amount,
                ];
            }
            return ExcelExporter::export('assistance_report', $headers, $rows);
        }

        $query = Patient::latest();
        if ($this->reportType === 'hemophilia_a') {
            $query->where('hemophilia_type', HemophiliaType::HemophiliaA);
        } elseif ($this->reportType === 'hemophilia_b') {
            $query->where('hemophilia_type', HemophiliaType::HemophiliaB);
        } elseif ($this->reportType === 'von_willebrand') {
            $query->where('hemophilia_type', HemophiliaType::VonWillebrand);
        } elseif ($this->reportType === 'other_bleeding') {
            $query->where('hemophilia_type', HemophiliaType::OtherBleeding);
        } elseif ($this->reportType === 'red_list') {
            $query->where('list_status', PatientListStatus::Red);
        } elseif ($this->reportType === 'incomplete_data') {
            $query->where('list_status', PatientListStatus::Yellow);
        }

        $patients = $query->get();

        $headers = [];
        if ($this->selectedColumns['patient_code']) $headers[] = 'کۆدی نەخۆش';
        if ($this->selectedColumns['full_name']) $headers[] = 'ناوی سیانی';
        if ($this->selectedColumns['phone']) $headers[] = 'ژمارەی مۆبایل';
        if ($this->selectedColumns['party_affiliation']) $headers[] = 'پەیوەندخوازە بە';
        if ($this->selectedColumns['voting_card_number']) $headers[] = 'کارتی دەنگدان';
        if ($this->selectedColumns['national_id']) $headers[] = 'کارتی نیشتمانی';
        if ($this->selectedColumns['address']) $headers[] = 'شوێنی نیشتەجێبوون';
        if ($this->selectedColumns['dob']) $headers[] = 'بەرواری لەدایکبوون';
        if ($this->selectedColumns['hemophilia_type']) $headers[] = 'جۆری نەخۆشی';
        if ($this->selectedColumns['membership_type']) $headers[] = 'پلەی ئەندامێتی';
        if ($this->selectedColumns['list_status']) $headers[] = 'دۆخ';

        $rows = [];
        foreach ($patients as $p) {
            $r = [];
            if ($this->selectedColumns['patient_code']) $r[] = $p->patient_code;
            if ($this->selectedColumns['full_name']) $r[] = $p->full_name;
            if ($this->selectedColumns['phone']) $r[] = $p->phone;
            if ($this->selectedColumns['party_affiliation']) $r[] = $p->party_affiliation?->label() ?? '—';
            if ($this->selectedColumns['voting_card_number']) $r[] = $p->voting_card_number ?? '—';
            if ($this->selectedColumns['national_id']) $r[] = $p->national_id ?? '—';
            if ($this->selectedColumns['address']) $r[] = $p->governorate . ' - ' . $p->district;
            if ($this->selectedColumns['dob']) $r[] = ($p->dob?->format('Y-m-d') ?? '—') . " ({$p->age} ساڵ)";
            if ($this->selectedColumns['hemophilia_type']) $r[] = $p->hemophilia_type?->label();
            if ($this->selectedColumns['membership_type']) $r[] = $p->membership_type?->label() ?? 'ئەندامی ئاسایی';
            if ($this->selectedColumns['list_status']) $r[] = $p->list_status->label();
            $rows[] = $r;
        }

        return ExcelExporter::export('patient_report', $headers, $rows);
    }

    public function render()
    {
        $data = collect();

        if ($this->reportType === 'all_patients') {
            $data = Patient::latest()->get();
        } elseif ($this->reportType === 'hemophilia_a') {
            $data = Patient::where('hemophilia_type', HemophiliaType::HemophiliaA)->get();
        } elseif ($this->reportType === 'hemophilia_b') {
            $data = Patient::where('hemophilia_type', HemophiliaType::HemophiliaB)->get();
        } elseif ($this->reportType === 'von_willebrand') {
            $data = Patient::where('hemophilia_type', HemophiliaType::VonWillebrand)->get();
        } elseif ($this->reportType === 'other_bleeding') {
            $data = Patient::where('hemophilia_type', HemophiliaType::OtherBleeding)->get();
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
