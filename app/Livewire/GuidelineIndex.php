<?php

namespace App\Livewire;

use App\Models\Guideline;
use App\Services\AuditLoggerService;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

/**
 * Association guidelines & decisions (membership conditions, member expulsions,
 * requests to directorates...) kept as PDF files with their date and title.
 */
class GuidelineIndex extends Component
{
    use WithFileUploads, WithPagination;

    public string $search = '';
    public string $year = '';

    public bool $showModal = false;
    public ?int $editingId = null;
    public string $title = '';
    public string $document_date = '';
    public string $reference_number = '';
    public string $description = '';
    public $file;

    public function mount()
    {
        $this->document_date = date('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingYear()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id)
    {
        $this->authorize('edit-records');

        $guideline = Guideline::findOrFail($id);

        $this->resetForm();
        $this->editingId = $guideline->id;
        $this->title = $guideline->title;
        $this->document_date = $guideline->document_date->format('Y-m-d');
        $this->reference_number = $guideline->reference_number ?? '';
        $this->description = $guideline->description ?? '';
        $this->showModal = true;
    }

    public function save()
    {
        $this->authorize('edit-records');

        $this->validate([
            'title' => 'required|string|max:255',
            'document_date' => 'required|date',
            'reference_number' => 'nullable|string|max:100',
            'file' => ($this->editingId ? 'nullable' : 'required') . '|file|mimes:pdf|max:20480',
        ], [
            'file.required' => 'فایلی PDF بار بکە.',
            'file.mimes' => 'تەنها فایلی PDF وەردەگیرێت.',
        ]);

        $data = [
            'title' => $this->title,
            'document_date' => $this->document_date,
            'reference_number' => $this->reference_number ?: null,
            'description' => $this->description ?: null,
        ];

        if ($this->file) {
            $data['file_path'] = $this->file->store('guidelines', 'local');
            $data['file_size'] = $this->file->getSize();
        }

        if ($this->editingId) {
            $guideline = Guideline::findOrFail($this->editingId);
            $old = $guideline->toArray();

            if ($this->file) {
                Storage::disk('local')->delete($guideline->file_path);
            }

            $guideline->update($data);
            AuditLoggerService::log('updated', $guideline, $old, $guideline->fresh()->toArray());
            session()->flash('message', 'ڕێنماییەکە نوێکرایەوە.');
        } else {
            $guideline = Guideline::create($data + ['user_id' => auth()->id()]);
            AuditLoggerService::log('created', $guideline, null, $guideline->toArray());
            session()->flash('message', 'ڕێنماییەکە بە سەرکەوتوویی تۆمارکرا.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id)
    {
        $this->authorize('delete-records');

        $guideline = Guideline::findOrFail($id);
        AuditLoggerService::log('deleted', $guideline, $guideline->toArray());
        Storage::disk('local')->delete($guideline->file_path);
        $guideline->delete();

        session()->flash('message', 'ڕێنماییەکە و فایلەکەی سڕدرانەوە.');
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'title', 'reference_number', 'description', 'file']);
        $this->document_date = date('Y-m-d');
        $this->resetValidation();
    }

    public function render()
    {
        $guidelines = Guideline::query()
            ->when($this->search, function ($q) {
                $s = '%'.$this->search.'%';
                $q->where(fn ($q) => $q->where('title', 'like', $s)
                    ->orWhere('reference_number', 'like', $s)
                    ->orWhere('description', 'like', $s));
            })
            ->when($this->year, fn ($q) => $q->whereYear('document_date', $this->year))
            ->orderByDesc('document_date')
            ->paginate(15);

        $years = Guideline::query()
            ->pluck('document_date')
            ->map(fn ($d) => $d->format('Y'))
            ->unique()
            ->sortDesc()
            ->values();

        return view('livewire.guideline-index', compact('guidelines', 'years'));
    }
}
