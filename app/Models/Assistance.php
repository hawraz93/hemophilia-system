<?php

namespace App\Models;

use App\Enums\AssistanceCategory;
use App\Enums\FundingSource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assistance extends Model
{
    use HasFactory;

    protected $fillable = [
        'assistance_number',
        'patient_id',
        'campaign_id',
        'assistance_date',
        'category',
        'amount',
        'source_funder',
        'funding_source',
        'notes',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'assistance_date' => 'date',
            'category' => AssistanceCategory::class,
            'funding_source' => FundingSource::class,
            'amount' => 'integer',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(AssistanceCampaign::class, 'campaign_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
