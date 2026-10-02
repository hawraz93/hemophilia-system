<?php

namespace App\Livewire;

use App\Enums\AssistanceCategory;
use App\Enums\FundingSource;
use App\Livewire\Concerns\SearchesPatients;
use App\Models\Assistance;
use App\Services\AidDistributionService;
use App\Services\AuditLoggerService;
use App\Services\CodeGenerator;
use App\Services\FinanceSummary;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class AssistanceIndex extends Component
{
    use SearchesPatients, WithPagination;

    public string $search = '';
    public string $filter_category = '';
    public string $filter_funding = '';

    public bool $showCreateModal = false;
    public ?int $patient_id = null;
    public string $assistance_date = '';
    public string $category = 'financial';
    public int $amount = 0;
    public string $source_funder = '';
    public string $funding_source = 'external';
    public string $notes = '';

    public function mount()
    {
        $this->assistance_date = date('Y-m-d');
    }

    public function openModal()
    {
        $this->showCreateModal = true;
    }

    public function save()
    {
        $this->authorize('edit-records');

        $this->validate([
            'patient_id' => 'required|exists:patients,id',
            'assistance_date' => 'required|date',
            'amount' => 'required|integer|min:0',
            'funding_source' => ['required', Rule::in(array_map(fn ($f) => $f->value, FundingSource::directOptions()))],
        ]);

        if ($error = FinanceSummary::insufficientFundsError(FundingSource::from($this->funding_source), $this->amount)) {
            $this->addError('amount', $error);
            return;
        }

        $assistanceNumber = CodeGenerator::next(Assistance::class, 'assistance_number', 'AID');

        $assistance = Assistance::create([
            'assistance_number' => $assistanceNumber,
            'patient_id' => $this->patient_id,
            'assistance_date' => $this->assistance_date,
            'category' => $this->category,
            'amount' => $this->amount,
            'source_funder' => $this->source_funder,
            'funding_source' => $this->funding_source,
            'notes' => $this->notes,
            'user_id' => auth()->id(),
        ]);

        AuditLoggerService::log('created', $assistance, null, $assistance->toArray());

        $this->showCreateModal = false;
        $this->reset(['patient_id', 'patientLookup', 'amount', 'source_funder', 'notes', 'funding_source']);
        session()->flash('message', 'هاوکاری نوێ بە سەرکەوتوویی تۆمارکرا.');
    }

    public function deleteAssistance(int $assistanceId)
    {
        $this->authorize('delete-records');

        $assistance = Assistance::with(['campaign', 'patient'])->findOrFail($assistanceId);

        if ($assistance->campaign && $assistance->patient) {
            // Returns the unit to the store as well
            AidDistributionService::revoke($assistance->campaign, $assistance->patient);
        } else {
            AuditLoggerService::log('deleted', $assistance, $assistance->toArray());
            $assistance->delete();
        }

        session()->flash('message', 'تۆماری هاوکارییەکە سڕدرایەوە.');
    }

    public function render()
    {
        $query = Assistance::with('patient');

        if ($this->search) {
            $s = '%'.$this->search.'%';
            $query->where(function ($q) use ($s) {
                $q->whereHas('patient', function ($p) use ($s) {
                    $p->where('full_name', 'like', $s)->orWhere('patient_code', 'like', $s);
                })->orWhere('assistance_number', 'like', $s);
            });
        }

        if ($this->filter_category) {
            $query->where('category', $this->filter_category);
        }

        if ($this->filter_funding) {
            $query->where('funding_source', $this->filter_funding);
        }

        $totalAmount = (clone $query)->sum('amount');
        $assistances = $query->latest()->paginate(15);
        $patientResults = $this->showCreateModal ? $this->patientLookupResults() : collect();
        $selectedPatient = $this->showCreateModal ? $this->selectedPatient() : null;

        return view('livewire.assistance-index', compact('assistances', 'totalAmount', 'patientResults', 'selectedPatient'));
    }
}
