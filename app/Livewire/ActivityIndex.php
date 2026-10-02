<?php

namespace App\Livewire;

use App\Models\Activity;
use App\Services\ExcelExporter;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $typeFilter = '';
    public string $statusFilter = '';

    // Modal state for Create / Edit
    public bool $showModal = false;
    public ?Activity $editingActivity = null;

    public string $title = '';
    public string $activity_type = 'seminar';
    public string $activity_date = '';
    public string $location = '';
    public string $organizer = '';
    public int $participants_count = 0;
    public int $budget = 0;
    public string $status = 'completed';
    public string $description = '';

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'activity_type' => 'required|string',
            'activity_date' => 'required|date',
            'participants_count' => 'required|integer|min:0',
            'budget' => 'required|integer|min:0',
            'status' => 'required|string',
        ];
    }

    public function mount()
    {
        $this->activity_date = date('Y-m-d');
    }

    public function openCreateModal()
    {
        $this->reset(['editingActivity', 'title', 'location', 'organizer', 'description']);
        $this->activity_type = 'seminar';
        $this->activity_date = date('Y-m-d');
        $this->participants_count = 0;
        $this->budget = 0;
        $this->status = 'completed';

        $this->showModal = true;
    }

    public function editActivity(Activity $activity)
    {
        $this->authorize('edit-records');

        $this->editingActivity = $activity;
        $this->title = $activity->title;
        $this->activity_type = $activity->activity_type;
        $this->activity_date = $activity->activity_date->format('Y-m-d');
        $this->location = $activity->location ?? '';
        $this->organizer = $activity->organizer ?? '';
        $this->participants_count = $activity->participants_count;
        $this->budget = $activity->budget;
        $this->status = $activity->status;
        $this->description = $activity->description ?? '';

        $this->showModal = true;
    }

    public function save()
    {
        $this->authorize('edit-records');

        $this->validate();

        $data = [
            'title' => $this->title,
            'activity_type' => $this->activity_type,
            'activity_date' => $this->activity_date,
            'location' => $this->location ?: null,
            'organizer' => $this->organizer ?: null,
            'participants_count' => $this->participants_count,
            'budget' => $this->budget,
            'status' => $this->status,
            'description' => $this->description ?: null,
        ];

        if ($this->editingActivity) {
            $this->editingActivity->update($data);
            session()->flash('message', 'زانیارییەکانی چالاکی بە سەرکەوتوویی نوێکرانەوە.');
        } else {
            Activity::create($data);
            session()->flash('message', 'چالاکی نوێ بە سەرکەوتوویی دروستکرا.');
        }

        $this->showModal = false;
    }

    public function deleteActivity(Activity $activity)
    {
        $this->authorize('delete-records');

        $activity->delete();
        session()->flash('message', 'چالاکی بە سەرکەوتوویی سڕدرایەوە.');
    }

    private function filteredQuery()
    {
        $query = Activity::latest('activity_date');

        if (!empty($this->search)) {
            $s = '%'.$this->search.'%';
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', $s)
                  ->orWhere('location', 'like', $s)
                  ->orWhere('organizer', 'like', $s);
            });
        }

        if (!empty($this->typeFilter)) {
            $query->where('activity_type', $this->typeFilter);
        }

        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        return $query;
    }

    public function exportExcel()
    {
        $activities = $this->filteredQuery()->get();

        $headers = ['ناونیشانی چالاکی', 'جۆری چالاکی', 'بەروار', 'شوێن', 'ڕێکخەر', 'بەشداربووان', 'بودجە (IQD)', 'دۆخ'];
        $rows = [];

        foreach ($activities as $act) {
            $rows[] = [
                $act->title,
                $act->activity_type_label,
                $act->activity_date->format('Y-m-d'),
                $act->location ?? '—',
                $act->organizer ?? '—',
                $act->participants_count,
                $act->budget,
                $act->status_label,
            ];
        }

        return ExcelExporter::export('activities_report', $headers, $rows);
    }

    public function render()
    {
        $activities = $this->filteredQuery()->paginate(15);
        $totalActivities = Activity::count();
        $totalParticipants = Activity::sum('participants_count');
        $totalBudget = Activity::sum('budget');

        return view('livewire.activity-index', compact('activities', 'totalActivities', 'totalParticipants', 'totalBudget'));
    }
}
