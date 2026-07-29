<?php

namespace Database\Seeders;

use App\Enums\AssistanceCategory;
use App\Enums\BloodGroup;
use App\Enums\ContactChannel;
use App\Enums\Gender;
use App\Enums\HemophiliaType;
use App\Enums\InfectiousStatus;
use App\Enums\MailDirection;
use App\Enums\MaritalStatus;
use App\Enums\MedicalLogType;
use App\Enums\MembershipStatus;
use App\Enums\PatientListStatus;
use App\Enums\SeverityLevel;
use App\Enums\UserRole;
use App\Models\Assistance;
use App\Models\MedicalLog;
use App\Models\Membership;
use App\Models\MembershipPayment;
use App\Models\OfficialMail;
use App\Models\Patient;
use App\Models\PatientContact;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@hemophilia.org'],
            [
                'name' => 'بەڕێوەبەری سیستەم (ئەدمین)',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role' => UserRole::Admin,
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'hawraz@gmail.com'],
            [
                'name' => 'هاوڕاز (ئەدمین)',
                'username' => 'hawraz',
                'password' => Hash::make('password'),
                'role' => UserRole::Admin,
                'is_active' => true,
            ]
        );

        $staff = User::firstOrCreate(
            ['email' => 'staff@hemophilia.org'],
            [
                'name' => 'کارمەندی ڕێکخراو',
                'username' => 'staff',
                'password' => Hash::make('password'),
                'role' => UserRole::Staff,
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'viewer@hemophilia.org'],
            [
                'name' => 'بینەری داتا',
                'username' => 'viewer',
                'password' => Hash::make('password'),
                'role' => UserRole::Viewer,
                'is_active' => true,
            ]
        );

        // 2. Patients
        if (Patient::count() === 0) {
        // Patient 1 - Green List
        $p1 = Patient::create([
            'patient_code' => 'PAT-2026-0001',
            'hiwa_code' => 'HW-9821',
            'membership_number' => 'MEM-101',
            'national_id' => '199587412356',
            'first_name' => 'ئارام',
            'father_name' => 'کامەران',
            'grandfather_name' => 'عەلی',
            'full_name' => 'ئارام کامەران عەلی',
            'gender' => Gender::Male,
            'dob' => '1995-04-12',
            'age' => 31,
            'marital_status' => MaritalStatus::Married,
            'children_count' => 2,
            'phone' => '07701234567',
            'secondary_phone' => '07501234567',
            'address' => 'سلێمانی - سەرچنار',
            'neighborhood' => 'سەرچنار',
            'district' => 'مەڵبەند 1',
            'governorate' => 'سلێمانی',
            'blood_group' => BloodGroup::OPositive,
            'hemophilia_type' => HemophiliaType::HemophiliaA,
            'severity' => SeverityLevel::Severe,
            'inhibitor_status' => InfectiousStatus::Negative,
            'hepatitis_b' => InfectiousStatus::Negative,
            'hepatitis_c' => InfectiousStatus::Negative,
            'hiv' => InfectiousStatus::Negative,
            'medical_notes' => 'نەخۆش پێویستی بە فاکتەر 8 هەفتانە هەیە.',
            'list_status' => PatientListStatus::Green,
            'last_contacted_at' => Carbon::now()->subDays(5),
        ]);

        // Patient 2 - Yellow List (Incomplete Data)
        $p2 = Patient::create([
            'patient_code' => 'PAT-2026-0002',
            'hiwa_code' => null,
            'membership_number' => 'MEM-102',
            'national_id' => null,
            'first_name' => 'شنیار',
            'father_name' => 'ئەحمەد',
            'grandfather_name' => 'حەسەن',
            'full_name' => 'شنیار ئەحمەد حەسەن',
            'gender' => Gender::Female,
            'dob' => '2010-08-20',
            'age' => 15,
            'marital_status' => MaritalStatus::Single,
            'children_count' => 0,
            'phone' => '07709876543',
            'address' => 'سلێمانی - بەختیاری',
            'neighborhood' => 'بەختیاری',
            'district' => 'مەڵبەند 2',
            'governorate' => 'سلێمانی',
            'blood_group' => BloodGroup::APositive,
            'hemophilia_type' => HemophiliaType::HemophiliaB,
            'severity' => SeverityLevel::Moderate,
            'inhibitor_status' => InfectiousStatus::Unknown,
            'hepatitis_b' => InfectiousStatus::Negative,
            'hepatitis_c' => InfectiousStatus::Negative,
            'hiv' => InfectiousStatus::Negative,
            'list_status' => PatientListStatus::Yellow,
            'last_contacted_at' => Carbon::now()->subDays(12),
        ]);

        // Patient 3 - Red List (Unresponsive / Old Contact)
        $p3 = Patient::create([
            'patient_code' => 'PAT-2026-0003',
            'hiwa_code' => 'HW-4410',
            'membership_number' => 'MEM-103',
            'national_id' => '198812345678',
            'first_name' => 'ڕێبوار',
            'father_name' => 'عومەر',
            'grandfather_name' => 'مەحمود',
            'full_name' => 'ڕێبوار عومەر مەحمود',
            'gender' => Gender::Male,
            'dob' => '1988-01-15',
            'age' => 38,
            'marital_status' => MaritalStatus::Married,
            'children_count' => 3,
            'phone' => '07700000000',
            'address' => 'چەمچەماڵ',
            'neighborhood' => 'ئاشتی',
            'district' => 'چەمچەماڵ',
            'governorate' => 'سلێمانی',
            'blood_group' => BloodGroup::BPositive,
            'hemophilia_type' => HemophiliaType::HemophiliaA,
            'severity' => SeverityLevel::Mild,
            'inhibitor_status' => InfectiousStatus::Negative,
            'hepatitis_b' => InfectiousStatus::Negative,
            'hepatitis_c' => InfectiousStatus::Positive,
            'hiv' => InfectiousStatus::Negative,
            'list_status' => PatientListStatus::Red,
            'unreachable_flag' => true,
            'last_contacted_at' => Carbon::now()->subDays(120),
        ]);

        // 3. Assistances
        Assistance::create([
            'assistance_number' => 'AID-2026-001',
            'patient_id' => $p1->id,
            'assistance_date' => Carbon::now()->subDays(10),
            'category' => AssistanceCategory::Financial,
            'amount' => 250000,
            'source_funder' => 'خێرخوازانی سلێمانی',
            'notes' => 'یارمەتی دارایی بۆ کڕینی دەرمانی تایبەت.',
            'user_id' => $admin->id,
        ]);

        Assistance::create([
            'assistance_number' => 'AID-2026-002',
            'patient_id' => $p1->id,
            'assistance_date' => Carbon::now()->subDays(2),
            'category' => AssistanceCategory::Medication,
            'amount' => 150000,
            'source_funder' => 'کۆمەڵەی هیمۆفیلیا',
            'notes' => 'دابینکردنی فاکتەر 8',
            'user_id' => $staff->id,
        ]);

        // 4. Memberships & Payments
        $m1 = Membership::create([
            'patient_id' => $p1->id,
            'registration_date' => '2024-01-01',
            'status' => MembershipStatus::Active,
            'due_amount' => 0,
            'notes' => 'ئەندامی هەڵسووراو',
        ]);

        MembershipPayment::create([
            'membership_id' => $m1->id,
            'patient_id' => $p1->id,
            'payment_date' => '2026-01-10',
            'amount_paid' => 25000,
            'receipt_number' => 'REC-9001',
            'notes' => 'رسوماتی ئەندامێتی ساڵی 2026',
            'user_id' => $staff->id,
        ]);

        // 5. Patient Contacts
        PatientContact::create([
            'patient_id' => $p1->id,
            'contact_date' => Carbon::now()->subDays(5),
            'channel' => ContactChannel::Phone,
            'outcome_notes' => 'پەیوەندی بە سەرکەوتوویی کراو دۆخی تەندروستی جێگیرە.',
            'is_successful' => true,
            'user_id' => $staff->id,
        ]);

        PatientContact::create([
            'patient_id' => $p3->id,
            'contact_date' => Carbon::now()->subDays(120),
            'channel' => ContactChannel::Phone,
            'outcome_notes' => 'ژمارەکە وەاڵم ناداتەوە.',
            'is_successful' => false,
            'user_id' => $staff->id,
        ]);

        // 6. Medical Logs
        MedicalLog::create([
            'patient_id' => $p1->id,
            'log_date' => Carbon::now()->subDays(15),
            'log_type' => MedicalLogType::HospitalVisit,
            'hospital_name' => 'نەخۆشخانەی هیوا',
            'factor_name_dose' => 'Factor VIII - 1000 IU',
            'details' => 'سەردان بۆ وەرگرتنی ژەمە فاکتەر.',
            'doctor_notes' => 'پشکنینی خوێن ئەنجامدرا.',
            'user_id' => $admin->id,
        ]);

        // 7. Official Mails
        OfficialMail::create([
            'mail_number' => 'MAIL-2026-088',
            'patient_id' => $p1->id,
            'direction' => MailDirection::Outgoing,
            'mail_date' => Carbon::now()->subDays(3),
            'sender_recipient' => 'نەخۆشخانەی هیوا',
            'reason_subject' => 'داواکاری هاوکاری و دابینکردنی فاکتەر',
            'notes' => 'ناردنی نوسراوی پشتگیری بۆ نەخۆشخانە.',
        ]);
        }
    }
}
