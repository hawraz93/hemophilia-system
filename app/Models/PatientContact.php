<?php

namespace App\Models;

use App\Enums\ContactChannel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'contact_date',
        'channel',
        'outcome_notes',
        'is_successful',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'contact_date' => 'date',
            'channel' => ContactChannel::class,
            'is_successful' => 'boolean',
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
