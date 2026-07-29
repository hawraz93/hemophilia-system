<?php

namespace App\Enums;

enum MailDirection: string
{
    case Incoming = 'incoming';
    case Outgoing = 'outgoing';

    public function label(): string
    {
        return match ($this) {
            self::Incoming => 'هاتوو (بەشی هاتوو)',
            self::Outgoing => 'ڕۆشتوو (بەشی ڕۆشتوو)',
        };
    }
}
