<?php

namespace App\Livewire;

use App\Models\MedicalLog;
use Livewire\Component;
use Livewire\WithPagination;

class MedicalIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function render()
    {
        $query = MedicalLog::with('patient');

        if ($this->search) {
            $s = '%'.$this->search.'%';
            $query->whereHas('patient', function ($q) use ($s) {
                $q->where('full_name', 'like', $s)->orWhere('patient_code', 'like', $s);
            })->orWhere('hospital_name', 'like', $s)->orWhere('factor_name_dose', 'like', $s);
        }

        $logs = $query->latest()->paginate(15);

        return view('livewire.medical-index', compact('logs'));
    }
}
