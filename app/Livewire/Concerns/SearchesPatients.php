<?php

namespace App\Livewire\Concerns;

use App\Models\Patient;
use Illuminate\Support\Collection;

/**
 * Search-as-you-type patient picker for components with a `$patient_id` property.
 * Only a handful of matches travel over the wire instead of the whole patient list,
 * which keeps every request small on slow connections.
 */
trait SearchesPatients
{
    public string $patientLookup = '';

    public function selectPatient(int $id): void
    {
        $this->patient_id = $id;
        $this->patientLookup = '';
    }

    public function clearPatient(): void
    {
        $this->patient_id = null;
    }

    protected function patientLookupResults(): Collection
    {
        $term = trim($this->patientLookup);
        if (mb_strlen($term) < 2) {
            return collect();
        }

        $s = '%'.$term.'%';

        return Patient::where(fn ($q) => $q->where('full_name', 'like', $s)
                ->orWhere('patient_code', 'like', $s)
                ->orWhere('membership_number', 'like', $s)
                ->orWhere('phone', 'like', $s))
            ->orderBy('full_name')
            ->take(10)
            ->get(['id', 'full_name', 'patient_code', 'phone']);
    }

    protected function selectedPatient(): ?Patient
    {
        return $this->patient_id ? Patient::find($this->patient_id, ['id', 'full_name', 'patient_code', 'phone']) : null;
    }
}
