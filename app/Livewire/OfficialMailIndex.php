<?php

namespace App\Livewire;

use App\Models\OfficialMail;
use App\Models\Patient;
use App\Services\AuditLoggerService;
use App\Services\CodeGenerator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class OfficialMailIndex extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $filter_direction = '';

    public bool $showModal = false;
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
            'body' => "ئاماژە بە تۆمارە فەرمییەکانی کۆمەڵەی هیمۆفیلیای کوردستان (لقی سلێمانی)، پشتگیری دەکەین کە هاووڵاتی نامبراو ئەندامی بەردەوام و تۆمارکراوی کۆمەڵەکەمانە بە دۆخی سەوز و تووشبووی نەخۆشی هیمۆفیلیاست. تکایە هاوکاری و ئاسانکاری پێویستی بۆ بکەن بۆ ڕاییکردنی مامەڵەکانی."
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

        $filePath = null;
        if ($this->file) {
            $filePath = $this->file->store('official_mails', 'local');
        }

        $mail = OfficialMail::create([
            'mail_number' => $this->mail_number,
            'patient_id' => $this->patient_id ?: null,
            'direction' => $this->direction,
            'stamp_type' => $this->stamp_type,
            'mail_date' => $this->mail_date,
            'sender_recipient' => $this->sender_recipient,
            'reason_subject' => $this->reason_subject,
            'letter_body' => $this->letter_body,
            'file_path' => $filePath,
        ]);

        AuditLoggerService::log('created', $mail, null, $mail->toArray());

        $this->showModal = false;
        $this->reset(['mail_number', 'patient_id', 'sender_recipient', 'reason_subject', 'letter_body', 'file', 'selected_template']);
        $this->mail_number = CodeGenerator::next(OfficialMail::class, 'mail_number', 'OFF');

        session()->flash('message', 'نوسراوی فەرمی بە سەرکەوتوویی تۆمارکرا.');
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
        $allPatients = Patient::orderBy('first_name')->get();

        return view('livewire.official-mail-index', compact('mails', 'allPatients'));
    }
}
