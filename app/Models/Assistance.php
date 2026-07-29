<?php

namespace App\Models;

use App\Enums\AssistanceCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assistance extends Model
{
    use HasFactory;

    protected $fillable = [
        'assistance_number',
        'patient_id',
        'assistance_date',
        'category',
        'amount',
        'source_funder',
        'notes',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'assistance_date' => 'date',
            'category' => AssistanceCategory::class,
            'amount' => 'integer',
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
