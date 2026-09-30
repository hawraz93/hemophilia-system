<?php

namespace App\Models;

use App\Enums\AssistanceCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AssistanceCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'source_funder',
        'amount_per_patient',
        'max_recipients',
        'campaign_date',
        'notes',
        'status',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'campaign_date' => 'date',
            'category' => AssistanceCategory::class,
            'amount_per_patient' => 'integer',
            'max_recipients' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function patients(): BelongsToMany
    {
        return $this->belongsToMany(Patient::class, 'assistance_campaign_patients', 'campaign_id', 'patient_id')
                    ->withPivot('received_at', 'notes')
                    ->withTimestamps();
    }

    public function getRecipientsCountAttribute(): int
    {
        return $this->patients()->count();
    }

    public function getIsFullAttribute(): bool
    {
        return $this->recipients_count >= $this->max_recipients;
    }
}
