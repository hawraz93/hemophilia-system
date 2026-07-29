<?php

namespace App\Livewire;

use App\Models\OfficialMail;
use App\Models\Patient;
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
    public string $direction = 'incoming';
    public string $mail_date = '';
    public string $sender_recipient = '';
    public string $reason_subject = '';
    public $file;

    public function mount()
    {
        $this->mail_date = date('Y-m-d');
    }

    public function save()
    {
        $this->validate([
            'mail_number' => 'required|string',
            'mail_date' => 'required|date',
            'sender_recipient' => 'required|string',
            'reason_subject' => 'required|string',
        ]);

        $filePath = null;
        if ($this->file) {
            $filePath = $this->file->store('official_mails', 'public');
        }

        OfficialMail::create([
            'mail_number' => $this->mail_number,
            'patient_id' => $this->patient_id ?: null,
            'direction' => $this->direction,
            'mail_date' => $this->mail_date,
            'sender_recipient' => $this->sender_recipient,
            'reason_subject' => $this->reason_subject,
            'file_path' => $filePath,
        ]);

        $this->showModal = false;
        $this->reset(['mail_number', 'patient_id', 'sender_recipient', 'reason_subject', 'file']);
        session()->flash('message', 'نوسراوی فەرمی بە سەرکەوتوویی تۆمارکرا.');
    }

    public function render()
    {
        $query = OfficialMail::with('patient');

        if ($this->search) {
            $s = '%'.$this->search.'%';
            $query->where('mail_number', 'like', $s)
                  ->orWhere('sender_recipient', 'like', $s)
                  ->orWhere('reason_subject', 'like', $s);
        }

        if ($this->filter_direction) {
            $query->where('direction', $this->filter_direction);
        }

        $mails = $query->latest()->paginate(15);
        $allPatients = Patient::orderBy('first_name')->get();

        return view('livewire.official-mail-index', compact('mails', 'allPatients'));
    }
}
