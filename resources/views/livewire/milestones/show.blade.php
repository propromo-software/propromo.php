<?php

use Livewire\Volt\Component;
use App\Models\Milestone;
use App\Models\Task;
use App\Traits\IssueCollector;

new class extends Component {

    use IssueCollector;

    public Milestone $milestone;

    public $scope;
    protected $queryString = ['scope'];
    private array $tasks = [];

    public function mount(Milestone $milestone)
    {
        $queryString = 'task';
        if ($milestone->tasks()->get()->isEmpty()) {
            $this->reloadIssues();
        }
        $this->milestone = $milestone;
    }

    public function reloadIssues()
    {
        try {
            $this->collect_tasks($this->milestone);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    protected function reload_tasks()
    {
        $labelName = $this->scope;

        if($labelName == 'sprint'){
            return $this->milestone->tasks()
                ->whereHas('labels', function($query) use ($labelName) {
                    $query->where('name', 'like', '%' . $labelName . '%');
                })
                ->get();
        }

        $tasksQuery = $this->milestone->tasks();

        if (!empty($labelName)) {
            $tasksQuery = $tasksQuery->whereHas('labels', function($query) use ($labelName) {
                $query->where('name', $labelName);
            });
        }

        return $tasksQuery->get();
    }
}; ?>


<div>
    <div class="p-8 mx-8 mt-4 rounded-2xl border-2 border-other-grey">
        <div class="flex justify-between items-center mb-5">

            <div class="flex gap-3 items-center">
                <a href="/monitors/{{$milestone->repository->monitor->id}}" title="Show Monitor" class="flex items-center">
                    <sl-icon class="p-2 text-4xl rounded-md border-2 cursor-pointer text-primary-blue border-other-grey" name="arrow-left-short" wire:ignore></sl-icon>
                </a>
                <div class="flex gap-1 items-center px-6 py-3 rounded-md border-2 border-other-grey"  title="Show User">

                    @if($milestone->progress >= 100.00)
                        <sl-icon wire:ignore class="text-xl font-bold text-additional-green font-sourceSansPro" name="check-circle"></sl-icon>
                        <div class="text-lg font-bold text-additional-green font-sourceSansPro">
                            {{strtoupper($milestone->title)}}
                        </div>
                    @else
                        <sl-icon wire:ignore class="text-xl font-bold text-additional-orange font-sourceSansPro" name="hammer"></sl-icon>
                        <div class="text-lg font-bold text-additional-orange font-sourceSansPro">
                            {{strtoupper($milestone->title)}}
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex gap-2 align-items-center">
                <sl-icon class="p-2 text-3xl rounded-md border-2 cursor-pointer text-primary-blue border-other-grey" name="filter" wire:ignore></sl-icon>

                <a href="/monitors/{{$milestone->repository->monitor->id}}/milestones/{{$milestone->id}}" title="Show Monitor" class="flex items-center">
                    <sl-icon class="p-2 text-3xl rounded-md border-2 cursor-pointer text-primary-blue border-other-grey" name="eraser" wire:ignore></sl-icon>
                </a>

                <sl-icon-button class="text-3xl text-secondary-grey" name="arrow-repeat" label="Reload" type="submit" wire:ignore wire:click="reloadIssues"></sl-icon-button>
            </div>

        </div>

        <div class="p-8 m-2 rounded-2xl border-2 border-other-grey">
            <livewire:tasks.list :tasks="$this->reload_tasks()"/>
        </div>
    </div>

    <x-footer :breadcrumbs="Breadcrumbs::generate('milestone', $milestone->repository->monitor, $milestone)" />
</div>
