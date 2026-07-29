<?php

namespace App\Enums;

enum ContactChannel: string
{
    case Phone = 'phone';
    case WhatsApp = 'whatsapp';
    case InPerson = 'in_person';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Phone => 'تەلەفۆن',
            self::WhatsApp => 'واتسئەپ',
            self::InPerson => 'سەردان',
            self::Other => 'ڕێگەی تر',
        };
    }
}
