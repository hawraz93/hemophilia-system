<?php

namespace App\Livewire;

use App\Enums\BloodGroup;
use App\Enums\Gender;
use App\Enums\HemophiliaType;
use App\Enums\InfectiousStatus;
use App\Enums\MaritalStatus;
use App\Enums\PatientListStatus;
use App\Enums\SeverityLevel;
use App\Models\Patient;
use App\Services\AuditLoggerService;
use App\Services\ExcelExporter;
use App\Services\PatientStatusEvaluator;
use Livewire\Component;
use Livewire\WithPagination;

class PatientIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filter_type = '';
    public string $filter_status = '';
    public string $filter_blood = '';

    public function mount()
    {
        if (request()->has('status')) {
            $this->filter_status = request()->query('status');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    private function filteredQuery()
    {
        $query = Patient::query();

        if ($this->search) {
            $s = '%'.$this->search.'%';
            $query->where(function ($q) use ($s) {
                $q->where('full_name', 'like', $s)
                  ->orWhere('patient_code', 'like', $s)
                  ->orWhere('membership_number', 'like', $s)
                  ->orWhere('hiwa_code', 'like', $s)
                  ->orWhere('phone', 'like', $s);
            });
        }

        if ($this->filter_type) {
            $query->where('hemophilia_type', $this->filter_type);
        }

        if ($this->filter_status) {
            $query->where('list_status', $this->filter_status);
        }

        if ($this->filter_blood) {
            $query->where('blood_group', $this->filter_blood);
        }

        return $query->latest();
    }

    public function exportExcel()
    {
        $patients = $this->filteredQuery()->get();

        $headers = [
            'کۆدی نەخۆش',
            'ناوی تەواو',
            'ژ. ئەندامێتی',
            'پلەی ئەندامێتی',
            'پەیوەندخوازە بە',
            'ژ. مۆبایل',
            'جۆری نەخۆشی',
            'گروپی خوێن',
            'کارتی دەنگدان',
            'کارتی نیشتمانی',
            'شوێنی نیشتەجێبوون',
            'دۆخی ئەندام',
        ];

        $rows = [];
        foreach ($patients as $p) {
            $rows[] = [
                $p->patient_code,
                $p->full_name,
                $p->membership_number ?? '—',
                $p->membership_type?->label() ?? 'ئەندامی ئاسایی',
                $p->party_affiliation?->label() ?? '—',
                $p->phone,
                $p->hemophilia_type?->label(),
                $p->blood_group?->value ?? '—',
                $p->voting_card_number ?? '—',
                $p->national_id ?? '—',
                $p->governorate . ' - ' . $p->district,
                $p->list_status->label(),
            ];
        }

        return ExcelExporter::export('patients_list', $headers, $rows);
    }

    public function render()
    {
        $patients = $this->filteredQuery()->paginate(15);

        return view('livewire.patient-index', compact('patients'));
    }
}
