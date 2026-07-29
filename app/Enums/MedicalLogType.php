<?php

namespace App\Enums;

enum MedicalLogType: string
{
    case HospitalVisit = 'hospital_visit';
    case BleedingEpisode = 'bleeding_episode';
    case Admission = 'admission';
    case Surgery = 'surgery';
    case LabTest = 'lab_test';
    case FactorUsage = 'factor_usage';
    case DoctorNote = 'doctor_note';

    public function label(): string
    {
        return match ($this) {
            self::HospitalVisit => 'سەردانی نەخۆشخانە',
            self::BleedingEpisode => 'خوێنڕشتن (Bleeding Episode)',
            self::Admission => 'داخڵبوونی نەخۆشخانە',
            self::Surgery => 'نەشتەرگەری',
            self::LabTest => 'تاقیکردنەوەی تاقیگە',
            self::FactorUsage => 'بەکارهێنانی Factor Concentrate',
            self::DoctorNote => 'تێبینی پزشکی',
        };
    }
}
