<?php

namespace App\Livewire\Monitors;

use App\Models\Contribution;
use App\Models\Monitor;
use App\Traits\ContributionCollector;
use Carbon\Carbon;
use Exception;
use Livewire\Component;

class ContributionsView extends Component
{
    use ContributionCollector;

    public Monitor $monitor;
    public $contributions = [];
    public $contributionId;

    public $commit;
    public function mount(Monitor $monitor, $contributionId = null)
    {
        $this->monitor = $monitor;
        $this->contributionId = $contributionId;
        $this->loadContributions();
        $this-> commit = $this->calculateContributions();
    }

    public function loadContributions()
    {
        try {
            $this->contributions = $this->collect_contributions($this->monitor);
        } catch (Exception $e) {
            $this->addError('contributions', $e->getMessage());
        }
    }


    public function calculateContributions()
    {
        $commits = Contribution::selectRaw('authors.name, COUNT(contributions.id) as commit_count')
            ->leftJoin('authors', 'contributions.author_id', '=', 'authors.id')
            ->where('committed_date', '>=', Carbon::now()->subDays(14))
            ->where('committed_date', '<', Carbon::now())
            ->groupBy('authors.name')
            ->get();

        return $commits;
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div class="flex justify-center mt-64">
           <div class="loader"></div>
        </div>
        HTML;
    }


    public function render()
    {
        return view('livewire.monitors.contributions-view');
    }
}
