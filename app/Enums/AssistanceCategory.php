<?php

namespace App\Enums;

enum AssistanceCategory: string
{
    case Financial = 'financial';
    case Medication = 'medication';
    case Food = 'food';
    case MedicalSupplies = 'medical_supplies';
    case Surgery = 'surgery';
    case Transport = 'transport';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Financial => 'هاوکاری دارایی',
            self::Medication => 'دەرمان',
            self::Food => 'خواردن و بەشەخۆراک',
            self::MedicalSupplies => 'کەرەستەی پزشکی',
            self::Surgery => 'نەشتەرگەری',
            self::Transport => 'گواستنەوە',
            self::Other => 'هاوکاری تر',
        };
    }
}
