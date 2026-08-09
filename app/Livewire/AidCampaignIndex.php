<?php

namespace App\Livewire;

use App\Enums\AssistanceCategory;
use App\Models\Assistance;
use App\Models\AssistanceCampaign;
use App\Models\Patient;
use App\Services\AuditLoggerService;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class AidCampaignIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $patientSearch = '';

    // Create Modal state
    public bool $showCreateModal = false;
    public string $title = '';
    public string $category = 'food';
    public string $source_funder = '';
    public int $amount_per_patient = 0;
    public int $max_recipients = 10;
    public string $campaign_date = '';
    public string $notes = '';

    // Selected Campaign for allocating patients
    public ?int $selectedCampaignId = null;
    public bool $showManageModal = false;

    public function mount()
    {
        $this->campaign_date = Carbon::now()->format('Y-m-d');
    }

    public function createCampaign()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'max_recipients' => 'required|integer|min:1',
            'campaign_date' => 'required|date',
        ]);

        $campaign = AssistanceCampaign::create([
            'title' => $this->title,
            'category' => $this->category,
            'source_funder' => $this->source_funder,
            'amount_per_patient' => $this->amount_per_patient,
            'max_recipients' => $this->max_recipients,
            'campaign_date' => $this->campaign_date,
            'notes' => $this->notes,
            'status' => 'active',
            'user_id' => auth()->id(),
        ]);

        $this->reset(['title', 'source_funder', 'notes']);
        $this->amount_per_patient = 0;
        $this->max_recipients = 10;
        $this->showCreateModal = false;

        session()->flash('message', 'کەمپین / هاوکاری گشتی نوێ دروستکرا.');
    }

    public function openManageModal(int $campaignId)
    {
        $this->selectedCampaignId = $campaignId;
        $this->patientSearch = '';
        $this->showManageModal = true;
    }

    public function addPatientToCampaign(int $patientId)
    {
        if (!$this->selectedCampaignId) return;

        $campaign = AssistanceCampaign::findOrFail($this->selectedCampaignId);

        if ($campaign->is_full) {
            session()->flash('error', 'سقفی دیاریکراوی ئەم هاوکارییە پڕبووەتەوە! ڕێگە بە زیادکردنی کەسی تر نادات.');
            return;
        }

        if ($campaign->patients()->where('patient_id', $patientId)->exists()) {
            session()->flash('error', 'ئەم نەخۆشە پێشتر بۆ ئەم هاوکارییە دابینکراوە.');
            return;
        }

        // Attach to campaign
        $campaign->patients()->attach($patientId, ['received_at' => now()]);

        // Also record in patient assistance history for individual tracking
        $patient = Patient::findOrFail($patientId);
        $assistanceNumber = 'AID-'.date('Y').'-'.str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

        Assistance::create([
            'assistance_number' => $assistanceNumber,
            'patient_id' => $patientId,
            'assistance_date' => $campaign->campaign_date,
            'category' => $campaign->category->value ?? $campaign->category,
            'amount' => $campaign->amount_per_patient,
            'source_funder' => $campaign->source_funder . ' (کەمپین: ' . $campaign->title . ')',
            'notes' => 'وەرگیراو لە کەمپینی هاوکاری گشتی: ' . $campaign->title,
            'user_id' => auth()->id(),
        ]);

        AuditLoggerService::log('assistance_campaign_patient_added', $patient);

        session()->flash('message', 'نەخۆش (' . $patient->full_name . ') بە سەرکەوتوویی زیادکرا.');
    }

    public function removePatientFromCampaign(int $patientId)
    {
        if (!$this->selectedCampaignId) return;

        $campaign = AssistanceCampaign::findOrFail($this->selectedCampaignId);
        $campaign->patients()->detach($patientId);

        session()->flash('message', 'نەخۆشەکە لە کەمپینەکە لابرایەوە.');
    }

    public function render()
    {
        $campaigns = AssistanceCampaign::withCount('patients')
            ->when($this->search, function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                  ->orWhere('source_funder', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(10);

        $selectedCampaign = $this->selectedCampaignId ? AssistanceCampaign::with('patients')->find($this->selectedCampaignId) : null;

        $eligiblePatients = [];
        if ($this->showManageModal && $selectedCampaign) {
            $existingIds = $selectedCampaign->patients->pluck('id')->toArray();
            $query = Patient::whereNotIn('id', $existingIds);
            if ($this->patientSearch) {
                $s = '%'.$this->patientSearch.'%';
                $query->where(function ($q) use ($s) {
                    $q->where('full_name', 'like', $s)
                      ->orWhere('patient_code', 'like', $s)
                      ->orWhere('membership_number', 'like', $s)
                      ->orWhere('phone', 'like', $s);
                });
            }
            $eligiblePatients = $query->take(12)->get();
        }

        return view('livewire.aid-campaign-index', compact('campaigns', 'selectedCampaign', 'eligiblePatients'));
    }
}
