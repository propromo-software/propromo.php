<?php

namespace App\Livewire\Monitors;

use App\Models\Monitor;
use App\Traits\VulnerabilityCollector;
use Livewire\Component;

class VulnerabilitiesView extends Component
{
    use VulnerabilityCollector;

    public Monitor $monitor;
    public $vulnerabilities = [];
    public $totalVulnerabilities = 0;

    public function mount(Monitor $monitor)
    {
        $this->monitor = $monitor;
        $this->loadVulnerabilities();
    }

    public function loadVulnerabilities()
    {
        try {
            //$result = $this->collect_vulnerabilities($this->monitor);
            $this->vulnerabilities = $this->monitor->vulnerabilities()->get();
            $this->totalVulnerabilities = $this->monitor->vulnerabilities()->count();
        } catch (\Exception $e) {
            $this->addError('vulnerabilities', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.monitors.vulnerabilities-view');
    }
}
