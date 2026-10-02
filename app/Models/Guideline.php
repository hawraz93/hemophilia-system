<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guideline extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'document_date',
        'reference_number',
        'description',
        'file_path',
        'file_size',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'document_date' => 'date',
            'file_size' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
