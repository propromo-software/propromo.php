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
    <div class="border-other-grey border-2 rounded-2xl p-8 mt-4 mx-8">
        <div class="flex items-center justify-between mb-5">

            <div class="flex items-center gap-3">
                <a href="/monitors/{{$milestone->repository->monitor->id}}" title="Show Monitor" class="flex items-center">
                    <sl-icon class="cursor-pointer text-primary-blue text-4xl rounded-md border-2 p-2 border-other-grey" name="arrow-left-short" wire:ignore></sl-icon>
                </a>
                <div class="flex items-center gap-1 rounded-md border-2 border-other-grey px-6 py-3"  title="Show User">

                    @if($milestone->progress >= 100.00)
                        <sl-icon wire:ignore class="text-additional-green font-sourceSansPro text-xl font-bold" name="check-circle"></sl-icon>
                        <div class="text-additional-green font-sourceSansPro text-lg font-bold">
                            {{strtoupper($milestone->title)}}
                        </div>
                    @else
                        <sl-icon wire:ignore class="text-additional-orange font-sourceSansPro text-xl font-bold" name="hammer"></sl-icon>
                        <div class="text-additional-orange font-sourceSansPro text-lg font-bold">
                            {{strtoupper($milestone->title)}}
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex gap-2 align-items-center">
                <sl-icon class="cursor-pointer text-primary-blue text-3xl rounded-md border-2 p-2 border-other-grey" name="filter" wire:ignore></sl-icon>

                <a href="/monitors/{{$milestone->repository->monitor->id}}/milestones/{{$milestone->id}}" title="Show Monitor" class="flex items-center">
                    <sl-icon class="cursor-pointer text-primary-blue text-3xl rounded-md border-2 p-2 border-other-grey" name="eraser" wire:ignore></sl-icon>
                </a>

                <sl-icon-button class="text-3xl text-secondary-grey" name="arrow-repeat" label="Reload" type="submit" wire:ignore wire:click="reloadIssues"></sl-icon-button>
            </div>

        </div>

        <div class="border-other-grey border-2 rounded-2xl p-8 m-2">
            <livewire:tasks.list :tasks="$this->reload_tasks()"/>
        </div>
    </div>

    {{ Breadcrumbs::render('milestone', $milestone->repository->monitor, $milestone) }}
</div>
