<?php

namespace App\Livewire\Monitors;

use App\Models\Monitor;
use App\Models\Contribution;
use Livewire\Component;
use Log;
use Exception;

class ContributionsView extends Component
{
    public Monitor $monitor;
    public $contributions = [];
    public $nextRootCursor = null;
    public $nextCursor = null;
    public bool $hasMoreRepositories = false;
    public bool $hasMoreCommits = false;
    public bool $loading = false;
    public ?string $currentRepositoryName = null;
    public ?string $error = null;

    public function mount(Monitor $monitor)
    {
        $this->monitor = $monitor;
        $this->loadNext();
    }

    public function loadNext()
    {
        try {
            $this->loading = true;
            $this->error = null;

            $result = $this->monitor->collect_contributions($this->nextRootCursor, $this->nextCursor);

            if (!$result) {
                throw new Exception('No response from API server');
            }

            Log::info('API Response:', [
                'hasMoreCommits' => $result['has_more_commits'] ?? false,
                'hasMoreRepositories' => $result['has_more_repositories'] ?? false,
                'nextRootCursor' => $result['next_root_cursor'] ?? null,
                'nextCursor' => $result['next_cursor'] ?? null,
                'currentRepo' => $result['current_repository_name'] ?? 'unknown',
                'contributionsCount' => count($result['contributions'] ?? [])
            ]);

            if (!empty($result['contributions'])) {
                $contributionIds = collect($result['contributions'])->pluck('id');
                $loadedContributions = Contribution::with('author')
                    ->whereIn('id', $contributionIds)
                    ->orderBy('committed_date', 'desc')
                    ->get();

                // Ensure objects are stored instead of arrays
                $this->contributions = array_merge($this->contributions, $loadedContributions->all());
            }

            $this->nextRootCursor = $result['next_root_cursor'];
            $this->nextCursor = $result['next_cursor'];
            $this->hasMoreRepositories = $result['has_more_repositories'] ?? false;
            $this->hasMoreCommits = $result['has_more_commits'] ?? false;
            $this->currentRepositoryName = $result['current_repository_name'];

        } catch (Exception $e) {
            $this->error = "Error loading contributions. Please try again.";
            $this->hasMoreCommits = false;
            $this->hasMoreRepositories = false;
            Log::error('Error in loadNext:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        } finally {
            $this->loading = false;
        }
    }

    public function loadMore()
    {
        if (!$this->loading) {
            if ($this->hasMoreCommits) {
                $this->loadNext();
            } elseif ($this->hasMoreRepositories) {
                $this->nextCursor = null;
                $this->loadNext();
            }
        }
    }

    public function retry()
    {
        $this->loadNext();
    }

    public function render()
    {
        return view('livewire.monitors.contributions-view', [
            'contributions' => $this->contributions
        ]);
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div class="flex justify-center mt-64">
           <div class="loader"></div>
        </div>
        HTML;
    }
}
