<?php

namespace App\Livewire;

use App\Models\PatientContact;
use Livewire\Component;
use Livewire\WithPagination;

class ContactIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function render()
    {
        $query = PatientContact::with('patient');

        if ($this->search) {
            $s = '%'.$this->search.'%';
            $query->whereHas('patient', function ($q) use ($s) {
                $q->where('full_name', 'like', $s)->orWhere('patient_code', 'like', $s);
            });
        }

        $contacts = $query->latest()->paginate(15);

        return view('livewire.contact-index', compact('contacts'));
    }
}
