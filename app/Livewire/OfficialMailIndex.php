<?php

namespace App\Livewire;

use App\Livewire\Concerns\SearchesPatients;
use App\Models\OfficialMail;
use App\Services\AuditLoggerService;
use App\Services\CodeGenerator;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class OfficialMailIndex extends Component
{
    use SearchesPatients, WithFileUploads, WithPagination;

    public string $search = '';
    public string $filter_direction = '';

    public bool $showModal = false;
    public ?int $editingMailId = null;
    public string $mail_number = '';
    public ?int $patient_id = null;
    public string $direction = 'outgoing';
    public string $stamp_type = 'online'; // 'online' or 'manual'
    public string $mail_date = '';
    public string $sender_recipient = '';
    public string $reason_subject = '';
    public string $letter_body = '';
    public string $selected_template = '';
    public $file;

    public array $templates = [
        'medication_request' => [
            'subject' => 'دابیىکردنی دەرمانی نەخۆشانی هیمۆفیلیا و خوێنبەربوون',
            'body' => "بەهۆی ئەو دۆخە نەخوازراوەی کە دروست بووە و نەخۆشانی هیمۆفیلیا دەرمانی پێویستیان نەماوە بۆ ژیانێکی ئاسایی و حکومەتی عێراق کە لێپرسراوە لە دابینکردنی دەرمان لە ئێستادا، داواکارین لە بەڕێزتان دەرمانی چارەسەری نەخۆشانی هیمۆفیلیا بۆ نەخۆشخانەی هیوا دابین بکەن لە شاری سلێمانی کە تاکە چارەسەری نەخۆشییەکەیان لەم ناوچەیەدا."
        ],
        'patient_support' => [
            'subject' => 'نوسراوی پشتگیری فەرمی',
            'body' => "سڵاو و ڕێز ...\nئاماژە بە تۆمارە فەرمییەکانی (کۆمەڵەی هیمۆفیلیای کوردستان / لقی سلێمانی)، پشتگیری دەکەین کە هاووڵاتی ئاماژەپێکراو ئەندامی بەردەوام و تۆمارکراوی کۆمەڵەکەمانە و نەخۆشی خوێنبەربوونی بۆماوەیی هەیە.\nبەپێی یاسا و ڕێنماییە کارپێکراوەکانی حکومەتی هەرێمی کوردستان ئەم کەسە خاوەنپێداویستی تایبەتە، تکایە هاوکاری و ئاسانکاری پێویستی بۆ بکەن بۆ ڕاییکردنی مامەڵەکانی، ئەم پشتگیرییەی لەسەر داوای خۆی بۆکراوە.\n\nلەگەڵ ڕێزدا ..."
        ],
        'official_thanks' => [
            'subject' => 'سوپاس و پێزانین',
            'body' => "بەناوی سەرۆکایەتی و سەرجەم ئەندامانی کۆمەڵەی هیمۆفیلیای کوردستان (لقی سلێمانی)، سوپاس و پێزانینی بێپایانی خۆمان ئاڕاستەی جەنابتان دەکەین بەرامبەر هاوکاری بەردەوامتان بۆ نەخۆشانی هیمۆفیلیا. بەهیوای سەرکەوتنی بەردەوامتان."
        ],
        'inquiry_letter' => [
            'subject' => 'داواکاری هاوکاری و هەماهەنگی',
            'body' => "سڵاو و ڕێز ... داوا لە بەڕێزتان دەکەین بە مەبەستی ڕاییکردنی مامەڵەکان و هەماهەنگی زیاتر لەنێوان کۆمەڵەکەمان و بەڕێوبەرایەتییەکەتان، ڕێنمایی پێویست لەم بارەیەوە بدەن."
        ]
    ];

    public function mount()
    {
        $this->mail_date = date('Y-m-d');
        $this->mail_number = CodeGenerator::next(OfficialMail::class, 'mail_number', 'OFF');
    }

    public function updatedSelectedTemplate($val)
    {
        if (isset($this->templates[$val])) {
            $this->reason_subject = $this->templates[$val]['subject'];
            $this->letter_body = $this->templates[$val]['body'];
        }
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function editMail(int $mailId)
    {
        $this->authorize('edit-records');

        $mail = OfficialMail::findOrFail($mailId);

        $this->resetForm();
        $this->editingMailId = $mail->id;
        $this->mail_number = $mail->mail_number;
        $this->patient_id = $mail->patient_id;
        $this->direction = $mail->direction->value;
        $this->stamp_type = $mail->stamp_type ?? 'online';
        $this->mail_date = $mail->mail_date->format('Y-m-d');
        $this->sender_recipient = $mail->sender_recipient;
        $this->reason_subject = $mail->reason_subject;
        $this->letter_body = $mail->letter_body ?? '';

        $this->showModal = true;
    }

    public function deleteMail(int $mailId)
    {
        $this->authorize('delete-records');

        $mail = OfficialMail::findOrFail($mailId);
        AuditLoggerService::log('deleted', $mail, $mail->toArray());

        if ($mail->file_path) {
            foreach (['local', 'public'] as $disk) {
                Storage::disk($disk)->delete($mail->file_path);
            }
        }
        $mail->delete();

        session()->flash('message', 'نوسراوەکە سڕدرایەوە.');
    }

    private function resetForm(): void
    {
        $this->reset(['editingMailId', 'patient_id', 'patientLookup', 'sender_recipient', 'reason_subject', 'letter_body', 'file', 'selected_template']);
        $this->direction = 'outgoing';
        $this->stamp_type = 'online';
        $this->mail_date = date('Y-m-d');
        $this->mail_number = CodeGenerator::next(OfficialMail::class, 'mail_number', 'OFF');
        $this->resetValidation();
    }

    public function save()
    {
        $this->authorize('edit-records');

        $this->validate([
            'mail_number' => 'required|string',
            'mail_date' => 'required|date',
            'sender_recipient' => 'required|string',
            'reason_subject' => 'required|string',
            'patient_id' => 'nullable|exists:patients,id',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp,doc,docx,xls,xlsx|max:10240',
        ]);

        $data = [
            'mail_number' => $this->mail_number,
            'patient_id' => $this->patient_id ?: null,
            'direction' => $this->direction,
            'stamp_type' => $this->stamp_type,
            'mail_date' => $this->mail_date,
            'sender_recipient' => $this->sender_recipient,
            'reason_subject' => $this->reason_subject,
            'letter_body' => $this->letter_body,
        ];

        if ($this->file) {
            $data['file_path'] = $this->file->store('official_mails', 'local');
        }

        if ($this->editingMailId) {
            $mail = OfficialMail::findOrFail($this->editingMailId);
            $old = $mail->toArray();

            // A newly uploaded file replaces the previous one
            if ($this->file && $mail->file_path) {
                foreach (['local', 'public'] as $disk) {
                    Storage::disk($disk)->delete($mail->file_path);
                }
            }

            $mail->update($data);
            AuditLoggerService::log('updated', $mail, $old, $mail->fresh()->toArray());
            session()->flash('message', 'نوسراوەکە نوێکرایەوە.');
        } else {
            $mail = OfficialMail::create($data);
            AuditLoggerService::log('created', $mail, null, $mail->toArray());
            session()->flash('message', 'نوسراوی فەرمی بە سەرکەوتوویی تۆمارکرا.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        $query = OfficialMail::with('patient');

        if ($this->search) {
            $s = '%'.$this->search.'%';
            $query->where(function ($q) use ($s) {
                $q->where('mail_number', 'like', $s)
                  ->orWhere('sender_recipient', 'like', $s)
                  ->orWhere('reason_subject', 'like', $s);
            });
        }

        if ($this->filter_direction) {
            $query->where('direction', $this->filter_direction);
        }

        $mails = $query->latest()->paginate(15);
        $patientResults = $this->showModal ? $this->patientLookupResults() : collect();
        $selectedPatient = $this->showModal ? $this->selectedPatient() : null;

        return view('livewire.official-mail-index', compact('mails', 'patientResults', 'selectedPatient'));
    }
}
