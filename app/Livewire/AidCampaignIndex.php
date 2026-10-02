<?php

namespace App\Livewire;

use App\Models\AssistanceCampaign;
use App\Models\Patient;
use App\Services\AidDistributionService;
use App\Services\AuditLoggerService;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use RuntimeException;

class AidCampaignIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $patientSearch = '';

    // Create / Edit modal state
    public bool $showCreateModal = false;
    public ?int $editingCampaignId = null;
    public string $title = '';
    public string $category = 'food';
    public string $source_funder = '';
    public int $amount_per_patient = 0;
    public int $max_recipients = 10;
    public string $campaign_date = '';
    public string $distribution_date = '';
    public string $notes = '';

    // Selected Campaign for allocating patients
    public ?int $selectedCampaignId = null;
    public bool $showManageModal = false;
    public string $handout_date = '';

    public function mount()
    {
        $this->campaign_date = Carbon::now()->format('Y-m-d');
        $this->handout_date = Carbon::now()->format('Y-m-d');
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function editCampaign(int $campaignId)
    {
        $this->authorize('edit-records');

        $campaign = AssistanceCampaign::findOrFail($campaignId);

        $this->editingCampaignId = $campaign->id;
        $this->title = $campaign->title;
        $this->category = $campaign->category->value ?? $campaign->category;
        $this->source_funder = $campaign->source_funder ?? '';
        $this->amount_per_patient = $campaign->amount_per_patient;
        $this->max_recipients = $campaign->max_recipients;
        $this->campaign_date = $campaign->campaign_date->format('Y-m-d');
        $this->distribution_date = $campaign->distribution_date?->format('Y-m-d') ?? '';
        $this->notes = $campaign->notes ?? '';

        $this->showCreateModal = true;
    }

    public function createCampaign()
    {
        $this->authorize('edit-records');

        $distributed = $this->editingCampaignId
            ? AssistanceCampaign::findOrFail($this->editingCampaignId)->patients()->count()
            : 0;

        $this->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'max_recipients' => 'required|integer|min:' . max(1, $distributed),
            'campaign_date' => 'required|date',
            'distribution_date' => 'nullable|date|after_or_equal:campaign_date',
            'amount_per_patient' => 'required|integer|min:0',
        ], [
            'max_recipients.min' => 'بڕی هاتوو نابێت لە ژمارەی دابەشکراو (' . $distributed . ') کەمتر بێت.',
            'distribution_date.after_or_equal' => 'بەرواری دابەشکردن نابێت پێش بەرواری گەیشتنی هاوکارییەکە بێت.',
        ]);

        $data = [
            'title' => $this->title,
            'category' => $this->category,
            'source_funder' => $this->source_funder,
            'amount_per_patient' => $this->amount_per_patient,
            'max_recipients' => $this->max_recipients,
            'campaign_date' => $this->campaign_date,
            'distribution_date' => $this->distribution_date ?: null,
            'notes' => $this->notes,
        ];

        if ($this->editingCampaignId) {
            $campaign = AssistanceCampaign::findOrFail($this->editingCampaignId);
            $old = $campaign->toArray();
            $campaign->update($data);
            AuditLoggerService::log('updated', $campaign, $old, $campaign->fresh()->toArray());
            session()->flash('message', 'زانیارییەکانی هاوکارییەکە نوێکرانەوە.');
        } else {
            $campaign = AssistanceCampaign::create($data + ['status' => 'active', 'user_id' => auth()->id()]);
            AuditLoggerService::log('created', $campaign, null, $campaign->toArray());
            session()->flash('message', 'کەمپین / هاوکاری گشتی نوێ دروستکرا.');
        }

        $this->resetForm();
        $this->showCreateModal = false;
    }

    public function deleteCampaign(int $campaignId)
    {
        $this->authorize('delete-records');

        $campaign = AssistanceCampaign::findOrFail($campaignId);
        AuditLoggerService::log('deleted', $campaign, $campaign->toArray());

        // Linked patient aid records are removed by the foreign key cascade
        $campaign->delete();

        if ($this->selectedCampaignId === $campaignId) {
            $this->showManageModal = false;
            $this->selectedCampaignId = null;
        }

        session()->flash('message', 'هاوکارییەکە و تۆمارەکانی دابەشکردنی سڕدرانەوە.');
    }

    public function openManageModal(int $campaignId)
    {
        $campaign = AssistanceCampaign::findOrFail($campaignId);

        $this->selectedCampaignId = $campaignId;
        $this->patientSearch = '';
        $this->handout_date = ($campaign->distribution_date && $campaign->distribution_date->isFuture())
            ? $campaign->distribution_date->format('Y-m-d')
            : Carbon::now()->format('Y-m-d');
        $this->showManageModal = true;
    }

    public function addPatientToCampaign(int $patientId)
    {
        $this->authorize('edit-records');

        if (!$this->selectedCampaignId) return;

        $this->validate(['handout_date' => 'required|date']);

        $campaign = AssistanceCampaign::findOrFail($this->selectedCampaignId);
        $patient = Patient::findOrFail($patientId);

        try {
            AidDistributionService::distribute($campaign, $patient, $this->handout_date);
        } catch (RuntimeException $e) {
            session()->flash('error', $e->getMessage());
            return;
        }

        session()->flash('message', 'نەخۆش (' . $patient->full_name . ') بە سەرکەوتوویی زیادکرا.');
    }

    public function removePatientFromCampaign(int $patientId)
    {
        $this->authorize('edit-records');

        if (!$this->selectedCampaignId) return;

        $campaign = AssistanceCampaign::findOrFail($this->selectedCampaignId);
        AidDistributionService::revoke($campaign, Patient::findOrFail($patientId));

        session()->flash('message', 'نەخۆشەکە لە کەمپینەکە لابرایەوە و دانەکە گەڕایەوە بۆ کۆگا.');
    }

    private function resetForm(): void
    {
        $this->reset(['editingCampaignId', 'title', 'source_funder', 'notes', 'distribution_date']);
        $this->category = 'food';
        $this->amount_per_patient = 0;
        $this->max_recipients = 10;
        $this->campaign_date = Carbon::now()->format('Y-m-d');
        $this->resetValidation();
    }

    public function render()
    {
        $campaigns = AssistanceCampaign::withCount('patients')
            ->when($this->search, function ($q) {
                $q->where(function ($q) {
                    $q->where('title', 'like', '%'.$this->search.'%')
                      ->orWhere('source_funder', 'like', '%'.$this->search.'%');
                });
            })
            ->latest()
            ->paginate(10);

        $stock = AssistanceCampaign::withCount('patients')->get(['id', 'max_recipients']);
        $stockTotals = [
            'received' => $stock->sum('max_recipients'),
            'distributed' => $stock->sum('patients_count'),
            'remaining' => $stock->sum(fn ($c) => max(0, $c->max_recipients - $c->patients_count)),
        ];

        $selectedCampaign = $this->selectedCampaignId
            ? AssistanceCampaign::withCount('patients')
                ->with(['patients' => fn ($q) => $q->orderBy('assistance_campaign_patients.received_at')])
                ->find($this->selectedCampaignId)
            : null;

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

        return view('livewire.aid-campaign-index', compact('campaigns', 'selectedCampaign', 'eligiblePatients', 'stockTotals'));
    }
}
