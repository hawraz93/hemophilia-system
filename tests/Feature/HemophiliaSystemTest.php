<?php

namespace Tests\Feature;

use App\Enums\BloodGroup;
use App\Enums\Gender;
use App\Enums\HemophiliaType;
use App\Enums\PatientListStatus;
use App\Enums\UserRole;
use App\Models\Patient;
use App\Models\User;
use App\Services\PatientStatusEvaluator;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HemophiliaSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_returns_successful_response(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => UserRole::Admin,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_patient_status_evaluator_classifies_lists_correctly(): void
    {
        // 1. Complete info -> Green list
        $patientGreen = Patient::create([
            'patient_code' => 'PAT-101',
            'first_name' => 'Aram',
            'father_name' => 'Kawa',
            'grandfather_name' => 'Ali',
            'full_name' => 'Aram Kawa Ali',
            'gender' => Gender::Male,
            'dob' => '1995-01-01',
            'phone' => '07701234567',
            'address' => 'Suleimani',
            'blood_group' => BloodGroup::OPositive,
            'membership_number' => 'MEM-101',
            'national_id' => '1234567890',
            'hiwa_code' => 'HW-101',
            'last_contacted_at' => Carbon::now()->subDays(5),
            'list_status' => PatientListStatus::Yellow,
        ]);

        $status = PatientStatusEvaluator::evaluate($patientGreen);
        $this->assertEquals(PatientListStatus::Green, $status);

        // 2. Unreachable / old contact -> Red list
        $patientRed = Patient::create([
            'patient_code' => 'PAT-102',
            'first_name' => 'Rebwar',
            'father_name' => 'Omar',
            'grandfather_name' => 'Mahmud',
            'full_name' => 'Rebwar Omar Mahmud',
            'gender' => Gender::Male,
            'phone' => '07700000000',
            'unreachable_flag' => true,
            'list_status' => PatientListStatus::Yellow,
        ]);

        $statusRed = PatientStatusEvaluator::evaluate($patientRed);
        $this->assertEquals(PatientListStatus::Red, $statusRed);
    }
}
