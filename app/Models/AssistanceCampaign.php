<?php

namespace App\Models;

use App\Enums\AssistanceCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'distribution_date',
        'notes',
        'status',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'campaign_date' => 'date',
            'distribution_date' => 'date',
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

    public function assistances(): HasMany
    {
        return $this->hasMany(Assistance::class, 'campaign_id');
    }

    /** Units still in the store (received quantity minus what has been handed out). */
    public function getRemainingCountAttribute(): int
    {
        return max(0, $this->max_recipients - ($this->patients_count ?? $this->recipients_count));
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
