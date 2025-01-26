<?php

namespace App\Livewire\Monitors;

use App\Models\Monitor;
use App\Traits\ReleaseCollector;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class ReleasesView extends Component
{
    use ReleaseCollector;

    public Monitor $monitor;
    public $releases = [];
    public $totalReleases = 0;
    public $selectedRepository = '';
    public $repositories = [];
    public $filteredCount = 0;
    public $isLoading = false;

    public function mount(Monitor $monitor)
    {
        $this->monitor = $monitor;
        $this->loadRepositories();
        $result = $this->collect_releases($this->monitor);
        $this->totalReleases = $result['total_count'];
        $this->loadReleases();
    }

    public function loadRepositories()
    {
        $this->repositories = $this->monitor->repositories()
            ->pluck('name', 'id')
            ->toArray();
    }

    public function loadReleases()
    {
        try {
            $this->isLoading = true;
            $query = $this->monitor->releases()->with(['tag.author', 'repository']);
            
            if ($this->selectedRepository) {
                Log::debug('Filtering by repository:', ['repository_id' => $this->selectedRepository]);
                $query->where('repository_id', $this->selectedRepository);
            }
            
            $this->releases = $query->orderByDesc('created_at')->get();
            Log::debug('Loaded releases:', ['count' => $this->releases->count()]);
            $this->filteredCount = $this->releases->count();
        } catch (\Exception $e) {
            $this->addError('releases', $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    public function render()
    {
        return view('livewire.monitors.releases-view');
    }
}
