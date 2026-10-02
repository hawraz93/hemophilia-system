<?php

namespace App\Livewire;

use App\Models\Patient;
use App\Services\AuditLoggerService;
use App\Services\CodeGenerator;
use App\Services\PatientStatusEvaluator;
use Illuminate\Validation\Rule;
use Livewire\Component;

class PatientForm extends Component
{
    public ?Patient $patient = null;
    public bool $isEditing = false;

    // Form fields
    public string $first_name = '';
    public string $father_name = '';
    public string $grandfather_name = '';
    public ?string $patient_code = null;
    public ?string $hiwa_code = null;
    public ?string $membership_number = null;
    public string $membership_type = 'ordinary';
    public ?string $party_affiliation = null;
    public ?string $voting_card_number = null;
    public ?string $national_id = null;
    public string $gender = 'male';
    public ?string $dob = null;
    public ?int $age = null;
    public ?string $marital_status = null;
    public ?int $children_count = 0;
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

    public function mount(?Patient $patient = null)
    {
        $this->authorize('edit-records');

        if ($patient && $patient->exists) {
            $this->patient = $patient;
            $this->isEditing = true;

            $this->patient_code = $patient->patient_code;
            $this->first_name = $patient->first_name;
            $this->father_name = $patient->father_name;
            $this->grandfather_name = $patient->grandfather_name;
            $this->hiwa_code = $patient->hiwa_code;
            $this->membership_number = $patient->membership_number;
            $this->membership_type = $patient->membership_type?->value ?? 'ordinary';
            $this->party_affiliation = $patient->party_affiliation?->value;
            $this->voting_card_number = $patient->voting_card_number;
            $this->national_id = $patient->national_id;
            $this->gender = $patient->gender->value;
            $this->dob = $patient->dob?->format('Y-m-d');
            $this->age = $patient->age;
            $this->marital_status = $patient->marital_status?->value;
            $this->children_count = $patient->children_count ?? 0;
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
        } else {
            $this->isEditing = false;
            $this->patient_code = CodeGenerator::next(Patient::class, 'patient_code', 'PAT');
        }
    }

    public function updatedDob($value)
    {
        if ($value) {
            $this->age = \Carbon\Carbon::parse($value)->age;
        }
    }

    public function save()
    {
        $this->authorize('edit-records');

        // The code shown on the form may have been taken by another user meanwhile
        if (! $this->isEditing) {
            $this->patient_code = CodeGenerator::next(Patient::class, 'patient_code', 'PAT');
        }

        $this->validate([
            'first_name' => 'required|string|max:100',
            'father_name' => 'required|string|max:100',
            'grandfather_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'hemophilia_type' => 'required|string',
            'membership_number' => [
                'nullable', 'string', 'max:50',
                Rule::unique('patients', 'membership_number')->ignore($this->patient?->id),
            ],
        ], [
            'first_name.required' => 'تکایە ناوی ناوخۆیی بنووسە.',
            'father_name.required' => 'تکایە ناوی باوک بنووسە.',
            'grandfather_name.required' => 'تکایە ناوی باپیر بنووسە.',
            'phone.required' => 'تکایە ژمارەی مۆبایل بنووسە.',
            'membership_number.unique' => 'ئەم ژمارەی ئەندامێتییە پێشتر بۆ نەخۆشێکی تر تۆمارکراوە.',
        ]);

        $fullName = trim("{$this->first_name} {$this->father_name} {$this->grandfather_name}");

        $data = [
            'patient_code' => $this->patient_code,
            'first_name' => $this->first_name,
            'father_name' => $this->father_name,
            'grandfather_name' => $this->grandfather_name,
            'full_name' => $fullName,
            'hiwa_code' => $this->hiwa_code,
            'membership_number' => $this->membership_number ?: null,
            'membership_type' => $this->membership_type,
            'party_affiliation' => $this->party_affiliation ?: null,
            'voting_card_number' => $this->voting_card_number,
            'national_id' => $this->national_id,
            'gender' => $this->gender,
            'dob' => $this->dob ?: null,
            'age' => $this->age ?: ($this->dob ? \Carbon\Carbon::parse($this->dob)->age : null),
            'marital_status' => $this->marital_status ?: null,
            'children_count' => $this->children_count ?? 0,
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

        if ($this->isEditing && $this->patient) {
            $oldValues = $this->patient->toArray();
            $this->patient->update($data);
            AuditLoggerService::log('updated', $this->patient, $oldValues, $this->patient->toArray());
            $savedPatient = $this->patient;
        } else {
            // On a slow line the same form can be submitted twice (double click, or a retry after
            // the first response was lost). Reuse the patient the first submission just created.
            $duplicate = Patient::where('full_name', $fullName)
                ->where('phone', $this->phone)
                ->where('created_at', '>=', now()->subMinutes(10))
                ->first();

            if ($duplicate) {
                session()->flash('message', 'ئەم نەخۆشە پێشتر پاشەکەوت کراوە.');

                return redirect()->route('patients.show', $duplicate);
            }

            $savedPatient = Patient::create($data);
            AuditLoggerService::log('created', $savedPatient, null, $savedPatient->toArray());

            // Any request already queued behind this one becomes an update, not a second insert
            $this->patient = $savedPatient;
            $this->isEditing = true;
        }

        // Evaluate list status (Green / Yellow / Red)
        PatientStatusEvaluator::evaluate($savedPatient);

        session()->flash('message', 'زانیارییەکانی نەخۆش بە سەرکەوتوویی پاشەکەوت کران.');

        return redirect()->route('patients.show', $savedPatient);
    }

    public function render()
    {
        return view('livewire.patient-form');
    }
}
