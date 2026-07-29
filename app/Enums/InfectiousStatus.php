<?php

namespace App\Enums;

enum InfectiousStatus: string
{
    case Positive = 'positive';
    case Negative = 'negative';
    case Unknown = 'unknown';

    public function label(): string
    {
        return match ($this) {
            self::Positive => 'پۆزەتیڤ (+)',
            self::Negative => 'نێگەتیڤ (-)',
            self::Unknown => 'نادیار / نەدیارییکراو',
        };
    }
}
