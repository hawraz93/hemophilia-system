<?php

namespace Tests\Feature;

use App\Enums\BloodGroup;
use App\Enums\Gender;
use App\Enums\PatientListStatus;
use App\Enums\UserRole;
use App\Livewire\AssistanceIndex;
use App\Livewire\PatientShow;
use App\Livewire\UserIndex;
use App\Models\Patient;
use App\Models\PatientDocument;
use App\Models\User;
use App\Services\CodeGenerator;
use App\Services\PatientStatusEvaluator;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class HemophiliaSystemTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(UserRole $role, array $attributes = []): User
    {
        static $n = 0;
        $n++;

        return User::create(array_merge([
            'name' => "User {$n}",
            'username' => "user{$n}",
            'email' => "user{$n}@test.com",
            'password' => 'password',
            'role' => $role,
            'is_active' => true,
        ], $attributes));
    }

    private function makePatient(array $attributes = []): Patient
    {
        return Patient::create(array_merge([
            'patient_code' => CodeGenerator::next(Patient::class, 'patient_code', 'PAT'),
            'first_name' => 'Aram',
            'father_name' => 'Kawa',
            'grandfather_name' => 'Ali',
            'full_name' => 'Aram Kawa Ali',
            'gender' => Gender::Male,
            'phone' => '07701234567',
        ], $attributes))->fresh();
    }

    public function test_login_page_returns_successful_response(): void
    {
        $this->get('/login')->assertStatus(200);
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $this->actingAs($this->makeUser(UserRole::Admin))
            ->get('/dashboard')
            ->assertStatus(200);
    }

    public function test_patient_status_evaluator_classifies_lists_correctly(): void
    {
        // 1. Complete form + document + membership payment -> Green list
        $patientGreen = $this->makePatient([
            'dob' => '1995-01-01',
            'address' => 'Suleimani',
            'blood_group' => BloodGroup::OPositive,
            'membership_number' => 'MEM-101',
            'national_id' => '1234567890',
            'last_contacted_at' => Carbon::now()->subDays(5),
        ]);
        $patientGreen->documents()->create(['title' => 'ID', 'file_path' => 'patient_documents/id.png']);
        $membership = $patientGreen->membership()->create(['registration_date' => now()]);
        $patientGreen->membershipPayments()->create([
            'membership_id' => $membership->id,
            'payment_date' => now(),
            'amount_paid' => 25000,
        ]);

        $this->assertEquals(PatientListStatus::Green, PatientStatusEvaluator::evaluate($patientGreen));

        // 2. Complete form but no documents / payment -> Yellow list
        $patientYellow = $this->makePatient([
            'dob' => '1995-01-01',
            'address' => 'Suleimani',
            'blood_group' => BloodGroup::OPositive,
            'membership_number' => 'MEM-102',
            'national_id' => '1234567891',
        ]);

        $this->assertEquals(PatientListStatus::Yellow, PatientStatusEvaluator::evaluate($patientYellow));

        // 3. Unreachable -> Red list
        $patientRed = $this->makePatient(['unreachable_flag' => true]);

        $this->assertEquals(PatientListStatus::Red, PatientStatusEvaluator::evaluate($patientRed));
    }

    public function test_only_admins_can_open_user_management(): void
    {
        $this->actingAs($this->makeUser(UserRole::Viewer))->get('/users')->assertForbidden();
        $this->actingAs($this->makeUser(UserRole::Staff))->get('/users')->assertForbidden();
        $this->actingAs($this->makeUser(UserRole::Admin))->get('/users')->assertOk();
    }

    public function test_admin_cannot_create_a_super_admin(): void
    {
        $this->actingAs($this->makeUser(UserRole::Admin));

        Livewire::test(UserIndex::class)
            ->set('name', 'Evil')
            ->set('username', 'evil')
            ->set('email', 'evil@test.com')
            ->set('password', 'password123')
            ->set('role', UserRole::SuperAdmin->value)
            ->call('save')
            ->assertHasErrors('role');

        $this->assertDatabaseMissing('users', ['username' => 'evil']);
    }

    public function test_admin_cannot_manage_higher_ranked_user(): void
    {
        $super = $this->makeUser(UserRole::SuperAdmin);
        $this->actingAs($this->makeUser(UserRole::Admin));

        Livewire::test(UserIndex::class)
            ->call('deleteUser', $super->id)
            ->assertForbidden();

        $this->assertDatabaseHas('users', ['id' => $super->id]);
    }

    public function test_viewer_cannot_write_records(): void
    {
        $patient = $this->makePatient();
        $this->actingAs($this->makeUser(UserRole::Viewer));

        $this->get('/patients/create')->assertForbidden();
        $this->get("/patients/{$patient->id}/edit")->assertForbidden();

        Livewire::test(PatientShow::class, ['patient' => $patient])
            ->set('aid_amount', 1000)
            ->call('addAssistance')
            ->assertForbidden();

        Livewire::test(AssistanceIndex::class)
            ->set('patient_id', $patient->id)
            ->set('amount', 1000)
            ->call('save')
            ->assertForbidden();

        $this->assertDatabaseCount('assistances', 0);
    }

    public function test_deactivated_user_is_logged_out(): void
    {
        $this->actingAs($this->makeUser(UserRole::Staff, ['is_active' => false]))
            ->get('/dashboard')
            ->assertRedirect('/login');

        $this->assertGuest();
    }

    public function test_documents_are_stored_privately_and_served_only_to_signed_in_users(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        $patient = $this->makePatient();
        $this->actingAs($this->makeUser(UserRole::Staff));

        Livewire::test(PatientShow::class, ['patient' => $patient])
            ->set('doc_title', 'Lab')
            ->set('doc_file', UploadedFile::fake()->create('lab.pdf', 100, 'application/pdf'))
            ->call('uploadDocument')
            ->assertHasNoErrors();

        $document = PatientDocument::firstOrFail();
        Storage::disk('local')->assertExists($document->file_path);
        Storage::disk('public')->assertMissing($document->file_path);

        $this->get(route('patient-documents.view', $document))->assertOk();

        auth()->logout();
        $this->get(route('patient-documents.view', $document))->assertRedirect('/login');
    }

    public function test_uploads_reject_dangerous_file_types(): void
    {
        Storage::fake('local');
        $patient = $this->makePatient();
        $this->actingAs($this->makeUser(UserRole::Staff));

        Livewire::test(PatientShow::class, ['patient' => $patient])
            ->set('doc_title', 'Bad')
            ->set('doc_file', UploadedFile::fake()->create('bad.html', 1, 'text/html'))
            ->call('uploadDocument')
            ->assertHasErrors('doc_file');
    }

    public function test_codes_are_sequential(): void
    {
        $year = date('Y');
        $this->makePatient(['patient_code' => "PAT-{$year}-0009"]);

        $this->assertSame("PAT-{$year}-0010", CodeGenerator::next(Patient::class, 'patient_code', 'PAT'));
    }

    public function test_login_is_rate_limited(): void
    {
        $this->makeUser(UserRole::Staff, ['username' => 'target']);

        $component = Livewire::test(\App\Livewire\Auth\Login::class)
            ->set('email_or_username', 'target')
            ->set('password', 'wrong');

        for ($i = 0; $i < 5; $i++) {
            $component->call('login');
        }

        $component->set('password', 'password')->call('login')->assertHasErrors('email_or_username');
        $this->assertGuest();
    }
}
