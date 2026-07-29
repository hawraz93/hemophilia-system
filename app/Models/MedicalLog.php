<?php

namespace App\Models;

use App\Enums\MedicalLogType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'log_date',
        'log_type',
        'hospital_name',
        'factor_name_dose',
        'details',
        'doctor_notes',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'log_date' => 'date',
            'log_type' => MedicalLogType::class,
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
