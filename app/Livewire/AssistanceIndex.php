<?php

namespace App\Livewire;

use App\Enums\AssistanceCategory;
use App\Models\Assistance;
use App\Models\Patient;
use Livewire\Component;
use Livewire\WithPagination;

class AssistanceIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filter_category = '';

    public bool $showCreateModal = false;
    public ?int $patient_id = null;
    public string $assistance_date = '';
    public string $category = 'financial';
    public int $amount = 0;
    public string $source_funder = '';
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
        $this->validate([
            'patient_id' => 'required|exists:patients,id',
            'assistance_date' => 'required|date',
            'amount' => 'required|integer|min:0',
        ]);

        $assistanceNumber = 'AID-'.date('Y').'-'.str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

        Assistance::create([
            'assistance_number' => $assistanceNumber,
            'patient_id' => $this->patient_id,
            'assistance_date' => $this->assistance_date,
            'category' => $this->category,
            'amount' => $this->amount,
            'source_funder' => $this->source_funder,
            'notes' => $this->notes,
            'user_id' => auth()->id(),
        ]);

        $this->showCreateModal = false;
        $this->reset(['patient_id', 'amount', 'source_funder', 'notes']);
        session()->flash('message', 'هاوکاری نوێ بە سەرکەوتوویی تۆمارکرا.');
    }

    public function render()
    {
        $query = Assistance::with('patient');

        if ($this->search) {
            $s = '%'.$this->search.'%';
            $query->whereHas('patient', function ($q) use ($s) {
                $q->where('full_name', 'like', $s)->orWhere('patient_code', 'like', $s);
            })->orWhere('assistance_number', 'like', $s);
        }

        if ($this->filter_category) {
            $query->where('category', $this->filter_category);
        }

        $totalAmount = (clone $query)->sum('amount');
        $assistances = $query->latest()->paginate(15);
        $allPatients = Patient::orderBy('first_name')->get();

        return view('livewire.assistance-index', compact('assistances', 'totalAmount', 'allPatients'));
    }
}
