<?php

namespace App\Livewire\Monitors;

use App\Models\Monitor;
use App\Traits\DeploymentCollector;
use Livewire\Component;
use Carbon\Carbon;

class DeploymentsView extends Component
{
    use DeploymentCollector;

    public Monitor $monitor;
    public $deployments = [];

    public function mount(Monitor $monitor)
    {
        $this->monitor = $monitor;
        $this->deployments = $monitor->deployments()->get();
        // $this->loadDeployments();
    }

    public function loadDeployments()
    {
        try {
            $this->deployments = $this->collect_deployments($this->monitor);
        } catch (\Exception $e) {
            $this->addError('deployments', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.monitors.deployments-view');
    }
}
