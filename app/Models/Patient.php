<?php

namespace App\Models;

use App\Enums\BloodGroup;
use App\Enums\Gender;
use App\Enums\HemophiliaType;
use App\Enums\InfectiousStatus;
use App\Enums\MaritalStatus;
use App\Enums\PatientListStatus;
use App\Enums\SeverityLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_code',
        'hiwa_code',
        'membership_number',
        'national_id',
        'first_name',
        'father_name',
        'grandfather_name',
        'full_name',
        'gender',
        'dob',
        'age',
        'marital_status',
        'children_count',
        'phone',
        'secondary_phone',
        'address',
        'neighborhood',
        'district',
        'governorate',
        'blood_group',
        'hemophilia_type',
        'severity',
        'inhibitor_status',
        'hepatitis_b',
        'hepatitis_c',
        'hiv',
        'comorbidities',
        'disability_special_needs',
        'medical_notes',
        'list_status',
        'unreachable_flag',
        'last_contacted_at',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'last_contacted_at' => 'datetime',
            'children_count' => 'integer',
            'age' => 'integer',
            'unreachable_flag' => 'boolean',
            'gender' => Gender::class,
            'marital_status' => MaritalStatus::class,
            'blood_group' => BloodGroup::class,
            'hemophilia_type' => HemophiliaType::class,
            'severity' => SeverityLevel::class,
            'inhibitor_status' => InfectiousStatus::class,
            'hepatitis_b' => InfectiousStatus::class,
            'hepatitis_c' => InfectiousStatus::class,
            'hiv' => InfectiousStatus::class,
            'list_status' => PatientListStatus::class,
        ];
    }

    public function documents(): HasMany
    {
        return $this->hasMany(PatientDocument::class);
    }

    public function assistances(): HasMany
    {
        return $this->hasMany(Assistance::class);
    }

    public function membership(): HasOne
    {
        return $this->hasOne(Membership::class);
    }

    public function membershipPayments(): HasMany
    {
        return $this->hasMany(MembershipPayment::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(PatientContact::class);
    }

    public function medicalLogs(): HasMany
    {
        return $this->hasMany(MedicalLog::class);
    }

    public function officialMails(): HasMany
    {
        return $this->hasMany(OfficialMail::class);
    }

    public function auditLogs(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable')->latest();
    }

    public function getPhotoAttribute(): ?string
    {
        $doc = $this->documents()->where('document_type', 'patient_photo')->latest()->first();
        return $doc ? $doc->file_path : null;
    }
}
