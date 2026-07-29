<?php

namespace App\Enums;

enum SeverityLevel: string
{
    case Mild = 'mild';
    case Moderate = 'moderate';
    case Severe = 'severe';

    public function label(): string
    {
        return match ($this) {
            self::Mild => 'سوک (Mild)',
            self::Moderate => 'ناوەند (Moderate)',
            self::Severe => 'سەخت (Severe)',
        };
    }
}
