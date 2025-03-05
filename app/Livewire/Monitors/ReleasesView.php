<?php

namespace App\Livewire\Monitors;

use App\Models\Monitor;
use App\Traits\ReleaseCollector;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Cache;

class ReleasesView extends Component
{
    use ReleaseCollector;
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public Monitor $monitor;
    protected $releases = [];
    public $totalReleases = 0;
    public $selectedRepository = '';
    public $repositories = [];
    public $filteredCount = 0;
    public $isLoading = false;
    public $perPage = 10;
    public $search = '';
    public $filterType = 'all'; // all, latest, prerelease, draft
    public $page = 1;
    public $preReleaseCount = 0;
    public $totalChanges = 0;
    public $totalFilesChanged = 0;

    protected $queryString = [
        'page' => ['as' => 'releasePage']
    ];

    public function mount(Monitor $monitor)
    {
        $this->monitor = $monitor;
        $this->loadRepositories();
       // $result = $this->collect_releases($this->monitor);
        $this->totalReleases = $this->monitor->releases()->count();
        $this->loadReleases();
    }

    public function loadRepositories()
    {
        $this->repositories = $this->monitor->repositories()
            ->pluck('name', 'id')
            ->toArray();
    }

    public function updatedSearch()
    {
        $this->resetPage();
        $this->loadReleases();
    }

    public function updatedSelectedRepository()
    {
        $this->resetPage();
        $this->loadReleases();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
        $this->loadReleases();
    }

    private function getCachedReleases($query)
    {
        $cacheKey = 'releases_' . $this->monitor->id . '_' .
                   $this->selectedRepository . '_' .
                   $this->filterType . '_' .
                   $this->search . '_' .
                   $this->page;

        return Cache::remember($cacheKey, now()->addMinutes(5), function() use ($query) {
            return $query->paginate($this->perPage);
        });
    }

    public function loadReleases()
    {
        try {
            $this->isLoading = true;
            $query = $this->monitor->releases()->with(['tag.author', 'repository']);

            // Initialize statistics with zero values
            $this->preReleaseCount = 0;
            $this->totalChanges = 0;
            $this->totalFilesChanged = 0;

            // Only calculate statistics if we have releases
            if ($this->monitor->releases()->exists()) {
                $this->preReleaseCount = $this->monitor->releases()->where('is_prerelease', true)->count();
                $allReleases = $this->monitor->releases()->with('tag')->get();
                $this->totalChanges = $allReleases->sum(fn($r) => $r->tag->additions + $r->tag->deletions);
                $this->totalFilesChanged = $allReleases->sum(fn($r) => $r->tag->changed_files);
            }

            if ($this->selectedRepository) {
                $query->where('repository_id', $this->selectedRepository);
            }

            switch ($this->filterType) {
                case 'latest':
                    $query->where('is_latest', true);
                    break;
                case 'prerelease':
                    $query->where('is_prerelease', true);
                    break;
                case 'draft':
                    $query->where('is_draft', true);
                    break;
            }

            if ($this->search) {
                $query->where(function($q) {
                    $searchTerm = strtolower($this->search);
                    $q->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"])
                      ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"]);
                });
            }

            $this->releases = $query->orderByDesc('created_at')
                ->paginate($this->perPage, ['*'], 'releasePage');
            $this->filteredCount = $this->releases->total();
        } catch (\Exception $e) {
            $this->addError('releases', $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    public function paginationView()
    {
        return 'pagination::tailwind';
    }

    public function render()
    {
        return view('livewire.monitors.releases-view', [
            'releases' => $this->releases
        ]);
    }

    public function getQueryString()
    {
        return [
            'page' => ['as' => 'releasePage']
        ];
    }
}
