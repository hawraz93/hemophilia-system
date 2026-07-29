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

    public bool $showCreateModal = false;
    public ?int $editingPatientId = null;

    // Form fields
    public string $first_name = '';
    public string $father_name = '';
    public string $grandfather_name = '';
    public ?string $patient_code = null;
    public ?string $hiwa_code = null;
    public ?string $membership_number = null;
    public ?string $national_id = null;
    public string $gender = 'male';
    public ?string $dob = null;
    public ?int $age = null;
    public ?string $marital_status = null;
    public int $children_count = 0;
    public string $phone = '';
    public ?string $secondary_phone = null;
    public ?string $address = null;
    public ?string $neighborhood = null;
    public ?string $district = null;
    public string $governorate = 'سلێمانی';
    public ?string $blood_group = null;
    public string $hemophilia_type = 'A';
    public string $severity = 'moderate';
    public string $inhibitor_status = 'unknown';
    public string $hepatitis_b = 'negative';
    public string $hepatitis_c = 'negative';
    public string $hiv = 'negative';
    public ?string $comorbidities = null;
    public ?string $disability_special_needs = null;
    public ?string $medical_notes = null;

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

    public function openCreateModal()
    {
        $this->resetForm();
        $this->patient_code = 'PAT-'.date('Y').'-'.str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $this->showCreateModal = true;
    }

    public function editPatient(Patient $patient)
    {
        $this->editingPatientId = $patient->id;
        $this->patient_code = $patient->patient_code;
        $this->first_name = $patient->first_name;
        $this->father_name = $patient->father_name;
        $this->grandfather_name = $patient->grandfather_name;
        $this->hiwa_code = $patient->hiwa_code;
        $this->membership_number = $patient->membership_number;
        $this->national_id = $patient->national_id;
        $this->gender = $patient->gender->value;
        $this->dob = $patient->dob?->format('Y-m-d');
        $this->age = $patient->age;
        $this->marital_status = $patient->marital_status?->value;
        $this->children_count = $patient->children_count;
        $this->phone = $patient->phone;
        $this->secondary_phone = $patient->secondary_phone;
        $this->address = $patient->address;
        $this->neighborhood = $patient->neighborhood;
        $this->district = $patient->district;
        $this->governorate = $patient->governorate;
        $this->blood_group = $patient->blood_group?->value;
        $this->hemophilia_type = $patient->hemophilia_type->value;
        $this->severity = $patient->severity->value;
        $this->inhibitor_status = $patient->inhibitor_status->value;
        $this->hepatitis_b = $patient->hepatitis_b->value;
        $this->hepatitis_c = $patient->hepatitis_c->value;
        $this->hiv = $patient->hiv->value;
        $this->comorbidities = $patient->comorbidities;
        $this->disability_special_needs = $patient->disability_special_needs;
        $this->medical_notes = $patient->medical_notes;

        $this->showCreateModal = true;
    }

    public function resetForm()
    {
        $this->editingPatientId = null;
        $this->patient_code = null;
        $this->first_name = '';
        $this->father_name = '';
        $this->grandfather_name = '';
        $this->hiwa_code = null;
        $this->membership_number = null;
        $this->national_id = null;
        $this->gender = 'male';
        $this->dob = null;
        $this->age = null;
        $this->marital_status = null;
        $this->children_count = 0;
        $this->phone = '';
        $this->secondary_phone = null;
        $this->address = null;
        $this->neighborhood = null;
        $this->district = null;
        $this->governorate = 'سلێمانی';
        $this->blood_group = null;
        $this->hemophilia_type = 'A';
        $this->severity = 'moderate';
        $this->inhibitor_status = 'unknown';
        $this->hepatitis_b = 'negative';
        $this->hepatitis_c = 'negative';
        $this->hiv = 'negative';
        $this->comorbidities = null;
        $this->disability_special_needs = null;
        $this->medical_notes = null;
    }

    public function save()
    {
        $this->validate([
            'first_name' => 'required|string|max:100',
            'father_name' => 'required|string|max:100',
            'grandfather_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'hemophilia_type' => 'required|string',
        ]);

        $fullName = trim("{$this->first_name} {$this->father_name} {$this->grandfather_name}");

        $data = [
            'patient_code' => $this->patient_code,
            'first_name' => $this->first_name,
            'father_name' => $this->father_name,
            'grandfather_name' => $this->grandfather_name,
            'full_name' => $fullName,
            'hiwa_code' => $this->hiwa_code,
            'membership_number' => $this->membership_number,
            'national_id' => $this->national_id,
            'gender' => $this->gender,
            'dob' => $this->dob ?: null,
            'age' => $this->age ?: ($this->dob ? \Carbon\Carbon::parse($this->dob)->age : null),
            'marital_status' => $this->marital_status ?: null,
            'children_count' => $this->children_count,
            'phone' => $this->phone,
            'secondary_phone' => $this->secondary_phone,
            'address' => $this->address,
            'neighborhood' => $this->neighborhood,
            'district' => $this->district,
            'governorate' => $this->governorate,
            'blood_group' => $this->blood_group ?: null,
            'hemophilia_type' => $this->hemophilia_type,
            'severity' => $this->severity,
            'inhibitor_status' => $this->inhibitor_status,
            'hepatitis_b' => $this->hepatitis_b,
            'hepatitis_c' => $this->hepatitis_c,
            'hiv' => $this->hiv,
            'comorbidities' => $this->comorbidities,
            'disability_special_needs' => $this->disability_special_needs,
            'medical_notes' => $this->medical_notes,
        ];

        if ($this->editingPatientId) {
            $patient = Patient::findOrFail($this->editingPatientId);
            $oldValues = $patient->toArray();
            $patient->update($data);
            AuditLoggerService::log('updated', $patient, $oldValues, $patient->toArray());
        } else {
            $patient = Patient::create($data);
            AuditLoggerService::log('created', $patient, null, $patient->toArray());
        }

        // Auto evaluate list status (Green / Yellow / Red)
        PatientStatusEvaluator::evaluate($patient);

        $this->showCreateModal = false;
        $this->resetForm();
        session()->flash('message', 'زانیاری نەخۆش بە سەرکەوتوویی پاشەکەوت کرا.');
    }

    public function render()
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

        $patients = $query->latest()->paginate(15);

        return view('livewire.patient-index', compact('patients'));
    }
}
