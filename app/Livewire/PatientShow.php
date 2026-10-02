<?php

namespace App\Livewire;

use App\Enums\AssistanceCategory;
use App\Enums\ContactChannel;
use App\Enums\DocumentType;
use App\Enums\FundingSource;
use App\Enums\MailDirection;
use App\Enums\MedicalLogType;

use App\Models\Assistance;
use App\Models\AssistanceCampaign;
use App\Models\MedicalLog;
use App\Models\MembershipPayment;
use App\Models\OfficialMail;
use App\Models\Patient;
use App\Models\PatientContact;
use App\Models\PatientDocument;
use App\Services\AidDistributionService;
use App\Services\AuditLoggerService;
use App\Services\CodeGenerator;
use App\Services\FinanceSummary;
use App\Services\PatientStatusEvaluator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use RuntimeException;

class PatientShow extends Component
{
    use WithFileUploads;

    public Patient $patient;
    public string $activeTab = 'info';

    // Document Upload Modal fields
    public bool $showDocModal = false;
    public string $doc_title = '';
    public string $doc_type = 'other';
    public $doc_file;

    // Assistance Modal fields
    public bool $showAidModal = false;
    public string $aid_date = '';
    public string $aid_category = 'financial';
    public int $aid_amount = 0;
    public string $aid_funder = '';
    public string $aid_notes = '';
    public string $aid_mode = 'direct'; // 'direct' or 'campaign' (hand out from received stock)
    public ?int $aid_campaign_id = null;
    public string $aid_funding_source = 'external';

    // Contact Log Modal fields
    public bool $showContactModal = false;
    public string $contact_date = '';
    public string $contact_channel = 'phone';
    public string $contact_notes = '';
    public bool $contact_successful = true;

    // Medical Log Modal fields
    public bool $showMedicalModal = false;
    public string $med_date = '';
    public string $med_type = 'hospital_visit';
    public string $med_hospital = '';
    public string $med_factor = '';
    public string $med_details = '';
    public string $med_notes = '';

    // Official Mail Modal fields
    public bool $showMailModal = false;
    public string $mail_number = '';
    public string $mail_direction = 'outgoing';
    public string $mail_date = '';
    public string $mail_sender = '';
    public string $mail_subject = '';
    public $mail_file;

    // Membership Payment Modal
    public bool $showPaymentModal = false;
    public int $pay_amount = 25000;
    public string $pay_date = '';
    public string $pay_receipt = '';
    public string $pay_notes = '';
    public bool $pay_exempt = false;

    public const EXEMPTION_NOTE = 'لێخۆشبوون لە پارەی ئەندامێتی ساڵانە بەپێی بڕیاری کارگێڕی کۆمەڵە (تێکڕای خاڵەکانی فۆرمی ئەندامبوون ٨٠ خاڵ و سەرووترە)';

    // Support Letter Customization Modal
    public bool $showSupportModal = false;
    public string $letter_recipient = 'لایەنی پەیوەندیدار';
    public string $letter_subject = 'نوسراوی پشتگیری';
    public string $letter_number = '';

    public function mount(Patient $patient)
    {
        $this->patient = $patient;
        $this->aid_date = Carbon::now()->format('Y-m-d');
        $this->contact_date = Carbon::now()->format('Y-m-d');
        $this->med_date = Carbon::now()->format('Y-m-d');
        $this->mail_date = Carbon::now()->format('Y-m-d');
        $this->pay_date = Carbon::now()->format('Y-m-d');
        $this->letter_number = 'SUP-' . date('Y') . '-' . str_pad($patient->id, 4, '0', STR_PAD_LEFT);
    }

    public function uploadDocument()
    {
        $this->authorize('edit-records');

        $this->validate([
            'doc_title' => 'required|string|max:255',
            'doc_file' => 'required|file|mimes:pdf,jpg,jpeg,png,webp,doc,docx,xls,xlsx|max:10240', // max 10MB
        ]);

        $path = $this->doc_file->store('patient_documents', 'local');

        PatientDocument::create([
            'patient_id' => $this->patient->id,
            'title' => $this->doc_title,
            'document_type' => $this->doc_type,
            'file_path' => $path,
            'file_size' => $this->doc_file->getSize(),
            'mime_type' => $this->doc_file->getMimeType(),
        ]);

        AuditLoggerService::log('document_uploaded', $this->patient);

        $this->showDocModal = false;
        $this->doc_title = '';
        $this->doc_file = null;
        $this->patient->refresh();

        PatientStatusEvaluator::evaluate($this->patient);

        session()->flash('message', 'بەڵگەنامە بە سەرکەوتوویی بارکرا.');
    }

    public function deleteDocument($docId)
    {
        $this->authorize('delete-records');

        $doc = PatientDocument::where('patient_id', $this->patient->id)->findOrFail($docId);
        foreach (['local', 'public'] as $disk) {
            Storage::disk($disk)->delete($doc->file_path);
        }
        $doc->delete();

        AuditLoggerService::log('document_deleted', $this->patient, ['title' => $doc->title, 'file_path' => $doc->file_path]);

        $this->patient->refresh();
        PatientStatusEvaluator::evaluate($this->patient);

        session()->flash('message', 'بەڵگەنامەکە بە سەرکەوتوویی سڕدرایەوە.');
    }

    public function addAssistance()
    {
        $this->authorize('edit-records');

        if ($this->aid_mode === 'campaign') {
            $this->validate([
                'aid_date' => 'required|date',
                'aid_campaign_id' => 'required|exists:assistance_campaigns,id',
            ], [
                'aid_campaign_id.required' => 'هاوکارییەک لە کۆگا هەڵبژێرە.',
            ]);

            try {
                AidDistributionService::distribute(AssistanceCampaign::findOrFail($this->aid_campaign_id), $this->patient, $this->aid_date);
            } catch (RuntimeException $e) {
                $this->addError('aid_campaign_id', $e->getMessage());
                return;
            }
        } else {
            $this->validate([
                'aid_date' => 'required|date',
                'aid_amount' => 'required|integer|min:0',
                'aid_funding_source' => ['required', Rule::in(array_map(fn ($f) => $f->value, FundingSource::directOptions()))],
            ]);

            $source = FundingSource::from($this->aid_funding_source);
            if ($error = FinanceSummary::insufficientFundsError($source, $this->aid_amount)) {
                $this->addError('aid_amount', $error);
                return;
            }

            $assistance = Assistance::create([
                'assistance_number' => CodeGenerator::next(Assistance::class, 'assistance_number', 'AID'),
                'patient_id' => $this->patient->id,
                'assistance_date' => $this->aid_date,
                'category' => $this->aid_category,
                'amount' => $this->aid_amount,
                'source_funder' => $this->aid_funder,
                'funding_source' => $source,
                'notes' => $this->aid_notes,
                'user_id' => auth()->id(),
            ]);

            AuditLoggerService::log('assistance_added', $this->patient, null, $assistance->toArray());
        }

        $this->showAidModal = false;
        $this->reset(['aid_amount', 'aid_funder', 'aid_notes', 'aid_campaign_id']);
        $this->patient->refresh();
        session()->flash('message', 'هاوکاری نوێ بۆ نەخۆش تۆمارکرا.');
    }

    public function deleteAssistance(int $assistanceId)
    {
        $this->authorize('delete-records');

        $assistance = Assistance::where('patient_id', $this->patient->id)->findOrFail($assistanceId);

        if ($assistance->campaign) {
            // Returns the unit to the store as well
            AidDistributionService::revoke($assistance->campaign, $this->patient);
        } else {
            AuditLoggerService::log('assistance_deleted', $this->patient, $assistance->toArray());
            $assistance->delete();
        }

        $this->patient->refresh();
        session()->flash('message', 'تۆماری هاوکارییەکە سڕدرایەوە.');
    }

    public function addContact()
    {
        $this->authorize('edit-records');

        $this->validate([
            'contact_date' => 'required|date',
        ]);

        PatientContact::create([
            'patient_id' => $this->patient->id,
            'contact_date' => $this->contact_date,
            'channel' => $this->contact_channel,
            'outcome_notes' => $this->contact_notes,
            'is_successful' => $this->contact_successful,
            'user_id' => auth()->id(),
        ]);

        // Update last contacted timestamp & reset unreachable flag if successful
        $this->patient->last_contacted_at = Carbon::parse($this->contact_date);
        if ($this->contact_successful) {
            $this->patient->unreachable_flag = false;
        }
        $this->patient->save();

        PatientStatusEvaluator::evaluate($this->patient);

        AuditLoggerService::log('contact_logged', $this->patient);

        $this->showContactModal = false;
        $this->patient->refresh();
        session()->flash('message', 'تۆماری پەیوەندی پاشەکەوت کرا.');
    }

    public function addMedicalLog()
    {
        $this->authorize('edit-records');

        $this->validate([
            'med_date' => 'required|date',
        ]);

        MedicalLog::create([
            'patient_id' => $this->patient->id,
            'log_date' => $this->med_date,
            'log_type' => $this->med_type,
            'hospital_name' => $this->med_hospital,
            'factor_name_dose' => $this->med_factor,
            'details' => $this->med_details,
            'doctor_notes' => $this->med_notes,
            'user_id' => auth()->id(),
        ]);

        AuditLoggerService::log('medical_log_added', $this->patient);

        $this->showMedicalModal = false;
        $this->patient->refresh();
        session()->flash('message', 'تۆماری پزیشکی پاشەکەوت کرا.');
    }

    public function addOfficialMail()
    {
        $this->authorize('edit-records');

        $this->validate([
            'mail_number' => 'required|string',
            'mail_date' => 'required|date',
            'mail_subject' => 'required|string',
            'mail_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp,doc,docx,xls,xlsx|max:10240',
        ]);

        $filePath = null;
        if ($this->mail_file) {
            $filePath = $this->mail_file->store('official_mails', 'local');
        }

        OfficialMail::create([
            'mail_number' => $this->mail_number,
            'patient_id' => $this->patient->id,
            'direction' => $this->mail_direction,
            'mail_date' => $this->mail_date,
            'sender_recipient' => $this->mail_sender,
            'reason_subject' => $this->mail_subject,
            'file_path' => $filePath,
        ]);

        AuditLoggerService::log('mail_created', $this->patient);

        $this->showMailModal = false;
        $this->patient->refresh();
        session()->flash('message', 'بەڵگەنامەی فەرمی تۆمارکرا.');
    }

    public function recordMembershipPayment()
    {
        $this->authorize('edit-records');

        if ($this->pay_exempt) {
            $this->pay_amount = 0;
        }

        $this->validate([
            'pay_amount' => $this->pay_exempt ? 'required|integer|in:0' : 'required|integer|min:1',
            'pay_date' => 'required|date',
        ], [
            'pay_amount.min' => 'بۆ بڕی سفر دینار، خانەی لێخۆشبوون هەڵبژێرە.',
        ]);

        $membership = $this->patient->membership;
        if (!$membership) {
            $membership = $this->patient->membership()->create([
                'registration_date' => Carbon::now(),
                'status' => 'active',
                'due_amount' => 0,
            ]);
        }

        MembershipPayment::create([
            'membership_id' => $membership->id,
            'patient_id' => $this->patient->id,
            'payment_date' => $this->pay_date,
            'amount_paid' => $this->pay_amount,
            'is_exempt' => $this->pay_exempt,
            'receipt_number' => $this->pay_receipt,
            'notes' => $this->pay_notes ?: ($this->pay_exempt ? self::EXEMPTION_NOTE : ''),
            'user_id' => auth()->id(),
        ]);

        AuditLoggerService::log('membership_payment_recorded', $this->patient);

        $this->showPaymentModal = false;
        $this->reset(['pay_receipt', 'pay_notes', 'pay_exempt']);
        $this->pay_amount = 25000;
        $this->patient->refresh();

        PatientStatusEvaluator::evaluate($this->patient);

        session()->flash('message', 'رسوماتی ئەندامێتی پاشەکەوت کرا.');
    }

    public function deleteMembershipPayment(int $paymentId)
    {
        $this->authorize('delete-records');

        $payment = MembershipPayment::where('patient_id', $this->patient->id)->findOrFail($paymentId);
        AuditLoggerService::log('membership_payment_deleted', $this->patient, $payment->toArray());
        $payment->delete();

        $this->patient->refresh();
        PatientStatusEvaluator::evaluate($this->patient);

        session()->flash('message', 'وەسڵی ئەندامێتی سڕدرایەوە.');
    }

    public function deleteMedicalLog(int $logId)
    {
        $this->authorize('delete-records');

        $log = MedicalLog::where('patient_id', $this->patient->id)->findOrFail($logId);
        AuditLoggerService::log('medical_log_deleted', $this->patient, $log->toArray());
        $log->delete();

        $this->patient->refresh();
        session()->flash('message', 'تۆماری پزیشکی سڕدرایەوە.');
    }

    public function deleteContact(int $contactId)
    {
        $this->authorize('delete-records');

        $contact = PatientContact::where('patient_id', $this->patient->id)->findOrFail($contactId);
        AuditLoggerService::log('contact_deleted', $this->patient, $contact->toArray());
        $contact->delete();

        $this->patient->refresh();
        session()->flash('message', 'تۆماری پەیوەندی سڕدرایەوە.');
    }

    /**
     * Permanently removes the patient and every record and file that belongs to them.
     */
    public function deletePatientPermanently()
    {
        $this->authorize('delete-records');

        $patient = $this->patient;
        $files = $patient->documents()->pluck('file_path');

        AuditLoggerService::log('patient_permanently_deleted', $patient, $patient->toArray());

        // Related rows (aid, hand-outs, membership, payments, contacts, medical logs, documents) cascade;
        // official letters are association records and are only unlinked (patient_id set to null)
        $patient->forceDelete();

        foreach ($files as $path) {
            foreach (['local', 'public'] as $disk) {
                Storage::disk($disk)->delete($path);
            }
        }

        session()->flash('message', 'نەخۆش (' . $patient->full_name . ') و هەموو تۆمارەکانی بە تەواوی سڕدرانەوە.');

        return $this->redirectRoute('patients.index');
    }

    public function render()
    {
        $availableCampaigns = collect();
        $finance = null;

        if ($this->showAidModal) {
            // Received aid batches that still have stock and that this patient has not received yet
            $availableCampaigns = AssistanceCampaign::withCount('patients')
                ->whereDoesntHave('patients', fn ($q) => $q->where('patients.id', $this->patient->id))
                ->orderByDesc('campaign_date')
                ->get()
                ->filter(fn ($c) => $c->patients_count < $c->max_recipients)
                ->values();
            $finance = FinanceSummary::get();
        }

        return view('livewire.patient-show', compact('availableCampaigns', 'finance'));
    }
}
