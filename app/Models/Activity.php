<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'activity_type',
        'activity_date',
        'location',
        'organizer',
        'participants_count',
        'budget',
        'status',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
            'participants_count' => 'integer',
            'budget' => 'integer',
        ];
    }

    public function getActivityTypeLabelAttribute(): string
    {
        return match ($this->activity_type) {
            'workshop' => 'وۆرکشۆپ',
            'seminar' => 'سیمینار',
            'medical_campaign' => 'هەڵمەتی پزیشکی',
            'assistance_distribution' => 'دابەشکردنی هاوکاری',
            'meeting' => 'کۆبوونەوە',
            'awareness' => 'هۆشیارکردنەوە',
            default => 'چالاکی گشتی',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'planned' => 'پلاندانراو',
            'completed' => 'ئەنجامدراو',
            'cancelled' => 'هەڵوەشێنراوە',
            default => 'نادیار',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'planned' => 'amber',
            'completed' => 'emerald',
            'cancelled' => 'rose',
            default => 'slate',
        };
    }
}
