<?php

use App\Models\Monitor;
use Livewire\Volt\Component;
use Barryvdh\DomPDF\Facade\Pdf;


new class extends Component {
    public Monitor $monitor;

    public $total_issues_open = 0;
    public $total_issues_closed = 0;
    public $total_issues = 0;
    public $total_repos = 0;
    public $total_percentage = 0;
    public $total_milestones = 0;
    public $top_milestones = [];
    public $tasks = [];

    public $data = [];

    public function mount(Monitor $monitor)
    {
        $this->monitor = $monitor;
        $this->calculate_statistics();

        $this->data = [
            'organization_name' => $this->monitor->organization_name,
            'organization_description' => $this->monitor->short_description,
            'total_issues' => $this->total_issues,
            'total_issues_open' => $this->total_issues_open,
            'total_issues_closed' => $this->total_issues_closed,
            'total_milestones' => $this->total_milestones,
            'total_percentage' => $this->total_percentage,
            'top_milestones' => $this->top_milestones,
            'generated_date' => now()->format('d-m-Y')
        ];
    }




    protected function calculate_statistics(): void
    {

        #$this->reload_issues();
        $allMilestonesEmpty = true;

        foreach ($this->monitor->repositories as $repository) {
            $repositoryTasks = $repository->milestones->flatMap(function ($milestone) {
                return $milestone->tasks;
            });

            $this->tasks = array_merge($this->tasks, $repositoryTasks->all());
        }

        if (empty($this->tasks)) {
            $this->reload_issues();
        }


        $this->total_repos = $this->monitor->repositories()->count();

        $this->total_issues_open = $this->monitor->repositories->flatMap(function ($repo) {
            return $repo->milestones->flatMap(function ($milestone) {
                return $milestone->tasks->whereNull('closed_at');
            });
        })->count();

        $this->total_issues_closed = $this->monitor->repositories->flatMap(function ($repo) {
            return $repo->milestones->flatMap(function ($milestone) {
                return $milestone->tasks->whereNotNull('closed_at');
            });
        })->count();

        $this->total_issues = $this->total_issues_open + $this->total_issues_closed;

        $this->top_milestones = $this->monitor->repositories->flatMap(function ($repo) {
            return $repo->milestones;
        })->sortByDesc('progress')->take(5);

        $totalMilestones = $this->monitor->repositories->flatMap(function ($repo) {
            return $repo->milestones;
        })->count();

        $this->total_milestones = $totalMilestones;

        if ($totalMilestones > 0) {
            $totalProgress = $this->monitor->repositories->flatMap(function ($repo) {
                return $repo->milestones->pluck('progress');
            })->sum();

            $this->total_percentage = round($totalProgress / $totalMilestones, 2);
        } else {
            $this->total_percentage = 0;
        }

        if ($this->total_issues > 0) {
            $this->total_percentage = round(($this->total_issues_closed / $this->total_issues) * 100, 2);
        } else {
            $this->total_percentage = 0;
        }
    }
    public function generate_pdf()
    {

        $pdf = Pdf::loadView('report-pdf', $this->data);

        return response()->streamDownload(function () use ($pdf){
            echo $pdf->stream();
        }, 'report-pdf.pdf');
    }

}; ?>

<div>
<div class="flex gap-8 p-8 bg-gray-100 min-h-screen">
    <!-- Configurator Section -->
    <div class="w-1/2 bg-white p-6 rounded-md border-2 border-other-grey px-6 py-6">
        <h2 class="text-4xl font-koulen text-primary-blue">Configurator</h2>
        <br>
        <sl-input wire:ignore placeholder="Organization-Name" value="{{$monitor->organization_name}}"></sl-input>
        <br>
        <sl-input wire:ignore placeholder="Organization-Description" value="{{$monitor->short_description}}"></sl-input>
        <br>

        <div class="flex flex-col space-y-4">
            <div class="flex-1">
                <label for="organization-name">Total Issues</label>
                <sl-input wire:ignore id="organization-name" class="w-full" placeholder="Organization Name"
                          value="{{$total_issues}}"></sl-input>

            </div>
            <div class="flex-1">
                <label for="organization-name">Open Issues</label>
                <sl-input wire:ignore id="organization-name" class="w-full" placeholder="Organization Name"
                          value="{{$total_issues_open}}"></sl-input>

            </div>
            <div class="flex-1">
                <label for="organization-name">Closed Issues</label>
                <sl-input wire:ignore id="organization-name" class="w-full" placeholder="Organization Name"
                          value="{{$total_issues_closed}}"></sl-input>

            </div>

            <div class="flex-1">
                <label for="organization-description">Total Milestones</label>
                <sl-input wire:ignore id="organization-description" class="w-full" placeholder="Organization Description"
                          value="{{$total_milestones}}"></sl-input>
            </div>

            <div class="flex-1">
                <label for="organization-description">Total Progess</label>
                <sl-input wire:ignore id="organization-description" class="w-full" placeholder=""
                          value="{{$total_percentage}} %"></sl-input>
            </div>


            <sl-button wire:ignore wire:click="generate_pdf()">Generate</sl-button>
        </div>
    </div>

    <!-- PDF Preview Section -->
    <div class="w-1/2 bg-white p-6 rounded-md border-2 border-other-grey px-6 py-6">
        <div class="p-8 bg-white text-gray-900">
            <!-- Header Section -->
            <div class="header text-center border-b-2 border-primary-blue pb-4 mb-8">
                <h1 class="text-2xl text-primary-blue font-semibold">Project Report</h1>
                <p class="text-lg text-gray-600">Organization: {{ $monitor->organization_name }}</p>
                <p class="text-lg text-gray-600">Description: {{ $monitor->organization_description }}</p>
            </div>

            <!-- Statistics Section -->
            <div class="section mb-8">
                <h2 class="text-xl text-primary-blue font-semibold mb-4">Statistics</h2>
                <div class="content p-4 border border-other-grey rounded-md bg-gray-50">
                    <p class="text-gray-700"><strong>Total Issues:</strong> {{ $total_issues }}</p>
                    <p class="text-gray-700"><strong>Open Issues:</strong> {{ $total_issues_open }}</p>
                    <p class="text-gray-700"><strong>Closed Issues:</strong> {{ $total_issues_closed }}</p>
                    <p class="text-gray-700"><strong>Total Milestones:</strong> {{ $total_milestones }}</p>
                    <p class="text-gray-700"><strong>Progress Percentage:</strong> {{ $total_percentage }}%</p>
                </div>
            </div>

            <!-- Top Milestones Section -->
            <div class="section mb-8">
                <h2 class="text-xl text-primary-blue font-semibold mb-4">Top Milestones</h2>
                <div class="content p-4 border border-other-grey rounded-md bg-gray-50">
                    <div class="milestones space-y-4">
                        @foreach ($top_milestones as $milestone)
                            <div class="milestone flex justify-between items-center border-b border-other-grey pb-2">
                                <span class="text-gray-700">{{ $milestone->name }}</span>
                                <div class="milestone-progress w-1/2 bg-other-grey rounded-full h-1 relative mx-4">
                                    <div class="progress-bar rounded-full h-1"
                                         style="width: {{ $milestone->progress }}%;
                                        background-color:
                                        @if($milestone->progress >= 75) #229342
                                        @elseif($milestone->progress >= 50) #FBC116
                                        @else #E33B2E
                                        @endif;">
                                    </div>
                                </div>
                                <span class="text-gray-700">{{ round($milestone->progress,2) }}%</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Footer Section -->
            <div class="footer text-center text-sm text-secondary-grey border-t border-other-grey pt-4 mt-8">
                <p>@Propromo  2024</p>
            </div>
        </div>

    </div>

</div>

{{ Breadcrumbs::render('pdf', $monitor) }}
</div>
