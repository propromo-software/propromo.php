<?php

use App\Traits\RepositoryIssueCollector;
use Livewire\Volt\Component;
use App\Models\Monitor;
use App\Traits\RepositoryCollector;

new class extends Component {

    use RepositoryIssueCollector;

    public Monitor $monitor;

    public function mount(Monitor $monitor): void
    {
        try {
            $this->collect_repository_issues($monitor);
            $this->monitor = $monitor;
        } catch (Exception $e) {
            $this->error_head = "Seems like something went wrong...";
        }
    }


};
?>

<div class="w-full p-5 items-center rounded-xl">
    <div class="flex items-center justify-between mb-5">
        <a class="text-secondary-grey text-lg font-sourceSansPro font-bold rounded-md border-2 border-other-grey px-6 py-3"
           href="/monitors/{{ $monitor->id }}" title="Show Monitor">
            {{ strtoupper($monitor->type == 'USER' ? $monitor->login_name : $monitor->organization_name) }}
            / {{ strtoupper($monitor->title) }}
        </a>
    </div>
    <livewire:monitors.custom.repositories.list :monitor_id="$monitor->id"></livewire:monitors.custom.repositories.list>
</div>
