<?php

use App\Models\Contribution;
use App\Models\Monitor;
use Carbon\Carbon;
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
    public $commitUsers = [];

    public $sprint_duration_weeks = 2;
    public $from_date = null;

    public $data = [];
    public $showData = false;

    public function mount(Monitor $monitor)
    {
        $this->monitor = $monitor;
        $this->calculate_statistics();
        $this->generate_data();
    }

    public function updated($property)
    {
        if ($property == 'sprint_duration_weeks' || $property == 'from_date') {
            $this->filter_commits();
        }
    }

    protected function calculate_statistics(): void
    {

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

    public function generate_data()
    {
        $this->data = [
            'organization_name' => $this->monitor->organization_name,
            'organization_description' => $this->monitor->short_description,
            'total_issues' => $this->total_issues,
            'total_issues_open' => $this->total_issues_open,
            'total_issues_closed' => $this->total_issues_closed,
            'total_milestones' => $this->total_milestones,
            'total_percentage' => $this->total_percentage,
            'top_milestones' => $this->top_milestones,
            'generated_date' => now()->format('d-m-Y'),
            'commitUsers' => $this->commitUsers
        ];

        $this->showData = true;

    }

    public function filter_commits()
    {
        $startDate = Carbon::parse($this->from_date ?: Carbon::now()->subWeeks($this->sprint_duration_weeks));
        $endDate = $startDate->copy()->addWeeks($this->sprint_duration_weeks);

        $commits = Contribution::selectRaw('authors.name, authors.avatar_url, COUNT(contributions.id) as commit_count')
            ->leftJoin('authors', 'contributions.author_id', '=', 'authors.id')
            ->whereBetween('contributions.committed_date', [$startDate, $endDate])
            ->groupBy('authors.name', 'authors.avatar_url')
            ->get();

        $this->commitUsers = $commits;
    }

    public function generate_pdf()
    {
        $this->generate_data();
        $pdf = Pdf::loadView('report-pdf', $this->data);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'report-pdf.pdf');
    }
}; ?>
<div>
    <div class="flex gap-8 p-8 bg-gray-100 min-h-screen">

        <div class="w-1/2 bg-white p-6 rounded-md border-2 border-other-grey px-6 py-6">
            <h2 class="text-4xl font-koulen text-primary-blue">Configurator</h2>
            <br>
            <sl-input wire:ignore placeholder="Organization-Name" value="{{$monitor->organization_name}}"></sl-input>
            <br>
            <sl-input wire:ignore placeholder="Organization-Description" value="{{$monitor->short_description}}"></sl-input>
            <br>

            <div class="flex flex-col space-y-4">
                <div class="flex-1">
                    <label for="sprint-duration">Sprint Duration (weeks)</label>
                    <sl-input wire:model="sprint_duration_weeks" class="w-full" type="number" placeholder="Sprint Duration in Weeks"></sl-input>
                </div>

                <div class="flex-1">
                    <label for="from_date">From Date</label>
                    <sl-input wire:model="from_date" class="w-full" type="date"></sl-input>
                </div>

                <sl-button wire:click="generate_data()">Generate</sl-button>

                @if($showData)
                    <sl-button wire:click="generate_pdf()">Download PDF</sl-button>
                @endif
            </div>
        </div>

        <div class="w-1/2 bg-white p-6 rounded-md border-2 border-other-grey px-6 py-6">
            @if($showData)
                <h2 class="text-xl text-center font-semibold text-primary-blue mb-4">Statistics</h2>

                    <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <p class="text-lg font-medium text-gray-700"><strong>Total Issues:</strong></p>
                        <p class="text-lg text-gray-800">{{ $total_issues }}</p>
                    </div>

                    <div class="flex justify-between items-center">
                        <p class="text-lg font-medium text-gray-700"><strong>Open Issues:</strong></p>
                        <p class="text-lg text-gray-800">{{ $total_issues_open }}</p>
                    </div>

                    <div class="flex justify-between items-center">
                        <p class="text-lg font-medium text-gray-700"><strong>Closed Issues:</strong></p>
                        <p class="text-lg text-gray-800">{{ $total_issues_closed }}</p>
                    </div>

                    <div class="flex justify-between items-center">
                        <p class="text-lg font-medium text-gray-700"><strong>Total Milestones:</strong></p>
                        <p class="text-lg text-gray-800">{{ $total_milestones }}</p>
                    </div>

                    <div class="flex justify-between items-center">
                        <p class="text-lg font-medium text-gray-700"><strong>Progress Percentage:</strong></p>
                        <p class="text-lg text-gray-800">{{ $total_percentage }}%</p>
                    </div>
                </div>

                <div class="w-full bg-white p-6 rounded-lg mt-6">
                    <h2 class="text-xl text-center font-semibold text-primary-blue mb-6">Top Milestones</h2>

                    <div class="space-y-6">
                        @foreach ($top_milestones as $milestone)
                            <span class="text-lg font-medium text-gray-700">{{ $milestone->title }}</span>
                            <div class="flex justify-between items-center  hover:bg-gray-100 transition duration-300">

                                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                    <div class="bg-primary-blue h-full" style="width: {{ min($milestone->progress, 100) }}%;"></div>
                                </div>

                                <div class="ml-2">
                                    <span class="text-lg font-semibold text-gray-800">{{ number_format($milestone->progress, 2) }}%</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>


                <h2 class="text-xl text-primary-blue font-semibold mt-4">Users & Commits</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">
                    @foreach($commitUsers as $user)
                        <div class="flex flex-col items-center bg-white p-4 rounded-md border border-other-grey">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full mb-4 border-2 border-other-grey">

                            <div class="text-center">
                                <h3 class="text-lg font-semibold text-primary-blue">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $user->commit_count }} commits</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{ Breadcrumbs::render('pdf', $monitor) }}
</div>
