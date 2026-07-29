<?php

namespace App\Models;

use App\Enums\MailDirection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfficialMail extends Model
{
    use HasFactory;

    protected $fillable = [
        'mail_number',
        'patient_id',
        'direction',
        'mail_date',
        'sender_recipient',
        'reason_subject',
        'file_path',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'mail_date' => 'date',
            'direction' => MailDirection::class,
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
