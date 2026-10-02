<?php

namespace Tests\Feature;

use App\Enums\FundingSource;
use App\Enums\Gender;
use App\Enums\UserRole;
use App\Livewire\ActivityIndex;
use App\Livewire\AidCampaignIndex;
use App\Livewire\GuidelineIndex;
use App\Livewire\PatientShow;
use App\Models\Activity;
use App\Models\Assistance;
use App\Models\AssistanceCampaign;
use App\Models\Guideline;
use App\Models\MembershipPayment;
use App\Models\Patient;
use App\Models\User;
use App\Services\AidDistributionService;
use App\Services\CodeGenerator;
use App\Services\FinanceSummary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use RuntimeException;
use Tests\TestCase;

class ClientFeedbackTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(UserRole $role): User
    {
        static $n = 0;
        $n++;

        return User::create([
            'name' => "User {$n}",
            'username' => "cf_user{$n}",
            'email' => "cf_user{$n}@test.com",
            'password' => 'password',
            'role' => $role,
            'is_active' => true,
        ]);
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

    private function makeCampaign(int $quantity, array $attributes = []): AssistanceCampaign
    {
        return AssistanceCampaign::create(array_merge([
            'title' => 'Qaiwan food basket',
            'category' => 'food',
            'source_funder' => 'Qaiwan',
            'amount_per_patient' => 0,
            'max_recipients' => $quantity,
            'campaign_date' => '2026-08-20',
            'distribution_date' => '2026-08-22',
            'status' => 'active',
        ], $attributes));
    }

    public function test_aid_report_prints_every_recipient(): void
    {
        $this->actingAs($this->makeUser(UserRole::Staff));
        $campaign = $this->makeCampaign(60);

        $patients = collect(range(1, 50))->map(fn ($i) => $this->makePatient(['full_name' => "Recipient Number {$i}"]));
        $patients->each(fn ($p) => AidDistributionService::distribute($campaign, $p, '2026-08-22'));

        $response = $this->get(route('aid-campaigns.print', $campaign))->assertOk();

        foreach ($patients as $p) {
            $response->assertSee($p->patient_code);
        }
        $response->assertSee('2026-08-22');
    }

    public function test_campaign_stores_distribution_date_and_tracks_remaining_stock(): void
    {
        $this->actingAs($this->makeUser(UserRole::Staff));

        Livewire::test(AidCampaignIndex::class)
            ->set('title', 'Qaiwan')
            ->set('max_recipients', 100)
            ->set('campaign_date', '2026-08-20')
            ->set('distribution_date', '2026-08-22')
            ->call('createCampaign')
            ->assertHasNoErrors();

        $campaign = AssistanceCampaign::firstOrFail();
        $this->assertSame('2026-08-22', $campaign->distribution_date->format('Y-m-d'));

        $patients = collect(range(1, 3))->map(fn () => $this->makePatient());
        $component = Livewire::test(AidCampaignIndex::class)->call('openManageModal', $campaign->id)->set('handout_date', '2026-08-23');
        $patients->each(fn ($p) => $component->call('addPatientToCampaign', $p->id));

        $campaign = AssistanceCampaign::withCount('patients')->find($campaign->id);
        $this->assertSame(3, $campaign->patients_count);
        $this->assertSame(97, $campaign->remaining_count);
        $this->assertSame(3, Assistance::where('campaign_id', $campaign->id)->whereDate('assistance_date', '2026-08-23')->count());

        // Returning a unit to the store also removes the patient's aid record
        $component->call('removePatientFromCampaign', $patients[0]->id);
        $this->assertSame(2, Assistance::where('campaign_id', $campaign->id)->count());
        $this->assertSame(98, AssistanceCampaign::withCount('patients')->find($campaign->id)->remaining_count);
    }

    public function test_distribution_date_cannot_be_before_arrival(): void
    {
        $this->actingAs($this->makeUser(UserRole::Staff));

        Livewire::test(AidCampaignIndex::class)
            ->set('title', 'Qaiwan')
            ->set('campaign_date', '2026-08-20')
            ->set('distribution_date', '2026-08-10')
            ->call('createCampaign')
            ->assertHasErrors('distribution_date');
    }

    public function test_cannot_hand_out_more_than_received(): void
    {
        $campaign = $this->makeCampaign(1);
        AidDistributionService::distribute($campaign, $this->makePatient());

        $this->expectException(RuntimeException::class);
        AidDistributionService::distribute($campaign, $this->makePatient());
    }

    public function test_patient_can_receive_aid_from_store_on_their_page(): void
    {
        $this->actingAs($this->makeUser(UserRole::Staff));
        $patient = $this->makePatient();
        $campaign = $this->makeCampaign(100);

        Livewire::test(PatientShow::class, ['patient' => $patient])
            ->set('showAidModal', true)
            ->set('aid_mode', 'campaign')
            ->set('aid_campaign_id', $campaign->id)
            ->set('aid_date', '2026-08-22')
            ->call('addAssistance')
            ->assertHasNoErrors();

        $this->assertTrue($campaign->patients()->where('patients.id', $patient->id)->exists());
        $this->assertSame(FundingSource::Campaign, Assistance::firstOrFail()->funding_source);
    }

    public function test_aid_from_membership_income_is_deducted_from_balances(): void
    {
        $this->actingAs($this->makeUser(UserRole::Staff));
        $member = $this->makePatient();

        Livewire::test(PatientShow::class, ['patient' => $member])
            ->set('pay_amount', 100000)
            ->call('recordMembershipPayment')
            ->assertHasNoErrors();

        Livewire::test(PatientShow::class, ['patient' => $this->makePatient()])
            ->set('aid_mode', 'direct')
            ->set('aid_funding_source', 'membership_income')
            ->set('aid_amount', 40000)
            ->call('addAssistance')
            ->assertHasNoErrors();

        $finance = FinanceSummary::get();
        $this->assertSame(100000, $finance['membership_income']);
        $this->assertSame(60000, $finance['membership_balance']);
        $this->assertSame(60000, $finance['general_balance']);

        // More than what is left in membership income is refused
        Livewire::test(PatientShow::class, ['patient' => $member])
            ->set('aid_mode', 'direct')
            ->set('aid_funding_source', 'membership_income')
            ->set('aid_amount', 70000)
            ->call('addAssistance')
            ->assertHasErrors('aid_amount');

        $this->assertSame(1, Assistance::count());
    }

    public function test_membership_fee_can_be_waived_with_zero_dinar(): void
    {
        $this->actingAs($this->makeUser(UserRole::Staff));
        $patient = $this->makePatient();

        // 0 without the exemption flag is rejected
        Livewire::test(PatientShow::class, ['patient' => $patient])
            ->set('pay_amount', 0)
            ->call('recordMembershipPayment')
            ->assertHasErrors('pay_amount');

        Livewire::test(PatientShow::class, ['patient' => $patient])
            ->set('pay_exempt', true)
            ->call('recordMembershipPayment')
            ->assertHasNoErrors();

        $payment = MembershipPayment::firstOrFail();
        $this->assertTrue($payment->is_exempt);
        $this->assertSame(0, $payment->amount_paid);
        $this->assertSame(PatientShow::EXEMPTION_NOTE, $payment->notes);
    }

    public function test_only_admins_can_delete_records(): void
    {
        $activity = Activity::create([
            'title' => 'Seminar', 'activity_type' => 'seminar', 'activity_date' => '2026-09-01',
            'participants_count' => 10, 'budget' => 0, 'status' => 'completed',
        ]);

        $this->actingAs($this->makeUser(UserRole::Staff));
        Livewire::test(ActivityIndex::class)->call('deleteActivity', $activity->id)->assertForbidden();
        $this->assertDatabaseHas('activities', ['id' => $activity->id]);

        $this->actingAs($this->makeUser(UserRole::Admin));
        Livewire::test(ActivityIndex::class)->call('deleteActivity', $activity->id);
        $this->assertDatabaseMissing('activities', ['id' => $activity->id]);
    }

    public function test_admin_can_permanently_delete_a_patient(): void
    {
        Storage::fake('local');
        $patient = $this->makePatient();
        $campaign = $this->makeCampaign(10);
        AidDistributionService::distribute($campaign, $patient);

        $this->actingAs($this->makeUser(UserRole::Staff));
        Livewire::test(PatientShow::class, ['patient' => $patient])->call('deletePatientPermanently')->assertForbidden();

        $this->actingAs($this->makeUser(UserRole::Admin));
        Livewire::test(PatientShow::class, ['patient' => $patient])
            ->set('doc_title', 'ID')
            ->set('doc_file', UploadedFile::fake()->create('id.pdf', 10, 'application/pdf'))
            ->call('uploadDocument');
        $path = $patient->documents()->value('file_path');
        Storage::disk('local')->assertExists($path);

        Livewire::test(PatientShow::class, ['patient' => $patient->fresh()])
            ->call('deletePatientPermanently')
            ->assertRedirect(route('patients.index'));

        $this->assertNull(Patient::withTrashed()->find($patient->id));
        $this->assertDatabaseCount('assistances', 0);
        $this->assertDatabaseCount('assistance_campaign_patients', 0);
        $this->assertDatabaseCount('patient_documents', 0);
        Storage::disk('local')->assertMissing($path);
    }

    public function test_support_letter_uses_new_wording_and_medical_fields(): void
    {
        $this->actingAs($this->makeUser(UserRole::Staff));
        $patient = $this->makePatient([
            'voting_card_number' => 'VOTE-555',
            'medical_notes' => 'Needs factor weekly',
            'disability_special_needs' => 'Knee joint damage',
            'comorbidities' => 'Hepatitis C',
        ]);

        $this->get(route('patients.support-letter', $patient))
            ->assertOk()
            ->assertSee('نەخۆشی خوێنبەربوونی بۆماوەیی هەیە')
            ->assertSee('Needs factor weekly')
            ->assertSee('Knee joint damage')
            ->assertSee('Hepatitis C')
            ->assertSee('بۆ بەڕێز :')
            ->assertSee('بابەت :')
            ->assertSee('images/stamp.png')
            ->assertDontSee('VOTE-555');
    }

    public function test_changed_pages_render_for_admins(): void
    {
        $this->actingAs($this->makeUser(UserRole::Admin));
        $patient = $this->makePatient();
        $campaign = $this->makeCampaign(5);
        AidDistributionService::distribute($campaign, $patient);
        MembershipPayment::create([
            'membership_id' => $patient->membership()->create(['registration_date' => now(), 'status' => 'active'])->id,
            'patient_id' => $patient->id, 'payment_date' => now(), 'amount_paid' => 0, 'is_exempt' => true,
        ]);
        Assistance::create([
            'assistance_number' => 'AID-X-1', 'patient_id' => $patient->id, 'assistance_date' => now(),
            'category' => 'financial', 'amount' => 0, 'funding_source' => FundingSource::MembershipIncome,
        ]);

        foreach (['aid-campaigns.index', 'assistances.index', 'memberships.index', 'mails.index', 'guidelines.index'] as $route) {
            $this->get(route($route))->assertOk();
        }
        $this->get(route('patients.show', $patient))->assertOk()->assertSee('سڕینەوەی تەواوی نەخۆش');
        $this->get(route('patients.id-card', $patient))->assertOk()->assertSee('images/id-card/front.jpg');
        $this->get(route('patients.summary-report', $patient))->assertOk();

        Livewire::test(PatientShow::class, ['patient' => $patient])
            ->set('showAidModal', true)
            ->set('aid_funding_source', 'membership_income')
            ->assertSee('باڵانسی داهاتی ئەندامێتی');

        Livewire::test(AidCampaignIndex::class)
            ->call('openManageModal', $campaign->id)
            ->assertSee('4 ماوە لە کۆگا');
    }

    public function test_double_submitted_patient_form_creates_one_patient(): void
    {
        $this->actingAs($this->makeUser(UserRole::Staff));

        $fill = fn ($component) => $component
            ->set('first_name', 'Aram')
            ->set('father_name', 'Kawa')
            ->set('grandfather_name', 'Ali')
            ->set('phone', '07701112233');

        // Same component sending save twice (second request queued behind the first)
        $component = $fill(Livewire::test(\App\Livewire\PatientForm::class));
        $component->call('save');
        $component->call('save');

        // A fresh form after the first response was lost
        $fill(Livewire::test(\App\Livewire\PatientForm::class))->call('save')->assertRedirect();

        $this->assertSame(1, Patient::where('phone', '07701112233')->count());
    }

    public function test_patient_picker_sends_only_matching_patients(): void
    {
        $this->actingAs($this->makeUser(UserRole::Staff));
        foreach (range(1, 30) as $i) {
            $this->makePatient(['full_name' => "Patient {$i}"]);
        }
        $target = $this->makePatient(['full_name' => 'Shilan Unique']);

        Livewire::test(\App\Livewire\AssistanceIndex::class)
            ->call('openModal')
            ->assertDontSee('Patient 7')
            ->set('patientLookup', 'Shilan')
            ->assertSee('Shilan Unique')
            ->assertDontSee('Patient 7')
            ->call('selectPatient', $target->id)
            ->assertSet('patient_id', $target->id);
    }

    public function test_pages_do_not_depend_on_external_fonts(): void
    {
        $this->actingAs($this->makeUser(UserRole::Staff));
        $patient = $this->makePatient();

        foreach ([route('dashboard'), route('patients.support-letter', $patient), route('patients.id-card', $patient)] as $url) {
            $this->get($url)->assertOk()->assertDontSee('fonts.googleapis.com')->assertSee('fonts/vazirmatn.css');
        }
        $this->assertFileExists(public_path('fonts/vazirmatn/Vazirmatn-wght.woff2'));
        $this->assertFileExists(public_path('offline.html'));
    }

    public function test_guidelines_accept_only_pdf_files(): void
    {
        Storage::fake('local');
        $this->actingAs($this->makeUser(UserRole::Staff));

        Livewire::test(GuidelineIndex::class)
            ->set('title', 'Membership conditions')
            ->set('document_date', '2020-03-18')
            ->set('file', UploadedFile::fake()->create('rules.docx', 10))
            ->call('save')
            ->assertHasErrors('file');

        Livewire::test(GuidelineIndex::class)
            ->set('title', 'Membership conditions')
            ->set('document_date', '2020-03-18')
            ->set('file', UploadedFile::fake()->create('rules.pdf', 10, 'application/pdf'))
            ->call('save')
            ->assertHasNoErrors();

        $guideline = Guideline::firstOrFail();
        Storage::disk('local')->assertExists($guideline->file_path);
        $this->get(route('guidelines.file', $guideline))->assertOk();
        $this->get(route('guidelines.index'))->assertOk()->assertSee('Membership conditions');
    }
}
