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
        $this->generate_data();
    }

    public function updated($property)
    {
        if ($property == 'sprint_duration_weeks' || $property == 'from_date') {
            $this->filter_commits();
        }
    }

    public function showCommitsAndUsers()
    {
        try {

            $commitsWithAuthors = Contribution::with('author')
                ->orderBy('committed_date', 'desc')
                ->get();

            $commitsData = $commitsWithAuthors->map(function ($contribution) {
                return [
                    'commit_message' => $contribution->message_headline,
                    'commit_url' => $contribution->commit_url,
                    'author_name' => $contribution->author->name ?? 'Unknown',
                    'author_id' => $contribution->author->id ?? 'N/A',
                    'committed_date' => $contribution->committed_date->format('Y-m-d H:i'),
                ];
            });

            return $commitsData;

        } catch (\Exception $e) {
            Log::error('Error fetching commits and user IDs:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return collect([]);
        }
    }

    public function calculateSprintStatistics($fromDate = null, $sprintDurationWeeks = 2)
    {
        // Calculate the sprint's start and end dates
        $startDate = Carbon::parse($fromDate ?: Carbon::now()->subWeeks($sprintDurationWeeks));
        $endDate = $startDate->copy()->addWeeks($sprintDurationWeeks);

        // Fetch contributions within the sprint's time frame
        $contributions = Contribution::with('author')
            ->whereBetween('committed_date', [$startDate, $endDate])
            ->get();

        // Calculate total commits
        $totalCommits = $contributions->count();

        // Group contributions by authors and calculate commit counts
        $commitsByAuthor = $contributions->groupBy('author_id')->map(function ($commits) {
            return [
                'author' => $commits->first()->author,
                'commit_count' => $commits->count(),
            ];
        });

        // Find the top committer
        $topCommitter = $commitsByAuthor->sortByDesc('commit_count')->first();

        // Calculate average commits per user
        $averageCommitsPerUser = $commitsByAuthor->pluck('commit_count')->avg() ?: 0;

        // Calculate total additions, deletions, and changed files
        $totalAdditions = $contributions->sum('additions');
        $totalDeletions = $contributions->sum('deletions');
        $totalChangedFiles = $contributions->sum('changed_files');

        return [
            'sprint_duration_weeks' => $sprintDurationWeeks,
            'sprint_start_date' => $startDate->format('d-m-Y'),
            'sprint_end_date' => $endDate->format('d-m-Y'),
            'total_commits' => $totalCommits,
            'top_committer' => $topCommitter ? $topCommitter['author']->name : 'N/A',
            'top_committer_commits' => $topCommitter['commit_count'] ?? 0,
            'average_commits_per_user' => number_format($averageCommitsPerUser, 2),
            'total_additions' => $totalAdditions,
            'total_deletions' => $totalDeletions,
            'total_changed_files' => $totalChangedFiles,
            'commits_by_author' => $commitsByAuthor->values(), // Collection of authors with commit counts
        ];
    }
    protected function calculate_statistics(): void
    {

        foreach ($this->monitor->repositories as $repository) {
            $repositoryTasks = $repository->milestones->flatMap(function ($milestone) {
                return $milestone->tasks;
            });

            $this->tasks = array_merge($this->tasks, $repositoryTasks->all());
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
        $this->calculate_statistics();
        // Calculate sprint-specific statistics
        $sprintStatistics = $this->calculateSprintStatistics($this->from_date, $this->sprint_duration_weeks);

        // Merge sprint statistics and other data into $this->data
        $this->data = array_merge($sprintStatistics, [
            'organization_name' => $this->monitor->organization_name,
            'organization_description' => $this->monitor->short_description,
            'total_issues' => $this->total_issues,
            'total_issues_open' => $this->total_issues_open,
            'total_issues_closed' => $this->total_issues_closed,
            'total_milestones' => $this->total_milestones,
            'repositories' => $this->monitor->repositories,
            'total_percentage' => $this->total_percentage,
            'top_milestones' => $this->top_milestones,
            'generated_date' => now()->format('d-m-Y'),
            'sprintStatistics' => $sprintStatistics
        ]);

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
    <div class="flex gap-8 p-8 bg-gray-100 h-min">

        <div class="p-6 px-6 py-6 w-1/2 bg-white rounded-md border-2 border-other-grey">
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

        <div class="p-6 px-6 py-6 w-1/2 bg-white rounded-md border-2 border-other-grey">
            @if($showData)
                <h2 class="mb-4 text-xl font-semibold text-center text-primary-blue">Statistics</h2>

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

                <div class="p-6 mt-6 w-full bg-white rounded-lg">
                    <h2 class="mb-6 text-xl font-semibold text-center text-primary-blue">Top Milestones</h2>

                    <div class="space-y-6">
                        @foreach ($top_milestones as $milestone)
                            <span class="text-lg font-medium text-gray-700">{{ $milestone->title }}</span>
                            <div class="flex justify-between items-center transition duration-300 hover:bg-gray-100">

                                <div class="w-full h-2 bg-gray-200 rounded-full">
                                    <div class="h-full bg-primary-blue" style="width: {{ min($milestone->progress, 100) }}%;"></div>
                                </div>

                                <div class="ml-2">
                                    <span class="text-lg font-semibold text-gray-800">{{ number_format($milestone->progress, 2) }}%</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>


                <h2 class="mt-4 text-xl font-semibold text-center text-primary-blue">Users & Commits</h2>
                <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($commitUsers as $user)
                        <div class="flex flex-col items-center p-4 bg-white rounded-md border border-other-grey">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="mb-4 w-24 h-24 rounded-full border-2 border-other-grey">

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

    <x-footer :breadcrumbs="Breadcrumbs::generate('pdf', $monitor)" />
</div>
