<?php

namespace App\Livewire\Monitors;

use App\Models\Monitor;
use App\Traits\ContributionCollector;
use Livewire\Component;

class ContributionsView extends Component
{
    use ContributionCollector;

    public Monitor $monitor;
    public $contributions = [];
    public $contributionId;

    public function mount(Monitor $monitor, $contributionId = null)
    {
        $this->monitor = $monitor;
        $this->contributionId = $contributionId;
        $this->loadContributions();
    }

    public function loadContributions()
    {
        try {
            $this->contributions = $this->collect_contributions($this->monitor);
        } catch (\Exception $e) {
            $this->addError('contributions', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.monitors.contributions-view');
    }
}
