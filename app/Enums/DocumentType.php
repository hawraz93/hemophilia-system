<?php

namespace App\Enums;

enum DocumentType: string
{
    case PatientPhoto = 'patient_photo';
    case NationalCard = 'national_card';
    case IDCard = 'id_card';
    case VotingCard = 'voting_card';
    case MedicalReport = 'medical_report';
    case LabResult = 'lab_result';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::PatientPhoto => 'وێنەی نەخۆش',
            self::NationalCard => 'کارتی نیشتمانی',
            self::IDCard => 'ناسنامە',
            self::VotingCard => 'کارتی دەنگدان',
            self::MedicalReport => 'ڕاپۆرتی پزشکی',
            self::LabResult => 'ئەنجامی تاقیکردنەوە',
            self::Other => 'بەڵگەنامەی تر',
        };
    }
}
