<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Report</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
        }

        /* Global Container */
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header Styling */
        .header {
            text-align: center;
            background-color: #0D3269;
            color: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 28px;
            margin: 0;
            font-weight: bold;
        }

        .header p {
            font-size: 16px;
            margin-top: 10px;
        }

        /* Section Styling */
        .section {
            margin-top: 10px;
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .section h2 {
            font-size: 22px;
            color: #0D3269;
            margin-bottom: 15px;
            border-bottom: 2px solid #0D3269;
            padding-bottom: 5px;
        }

        .section p {
            font-size: 14px;
            color: #555;
        }

        /* Statistics Table */
        .statistics-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .statistics-cell {
            padding: 10px;
            text-align: center;
        }

        .statistics-value {
            font-size: 20px;
            font-weight: bold;
        }

        .statistics-description {
            font-size: 14px;
            color: #555;
        }

        /* Milestone Styling */
        .milestone {
            padding: 10px 0;
            border-bottom: 1px solid #e5e5e5;
        }

        .milestone-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .milestone:last-child {
            border-bottom: none;
        }

        .progress-bar-container {
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
            height: 12px;
            margin-top: 5px;
        }

        .progress-bar {
            height: 100%;
            background-color: #0D3269;
        }

        .progress-percentage {
            font-size: 14px;
            font-weight: bold;
            color: #555;
            margin-top: 5px;
        }

        /* User Commit Styling */
        .user-commit {
            padding: 10px 0;
            border-bottom: 1px solid #e5e5e5;
        }

        .user-commit:last-child {
            border-bottom: none;
        }

        .user-commit strong {
            color: #0D3269;
        }

        /* Footer Styling */
        .footer {
            text-align: center;
            font-size: 12px;
            color: #aaa;
            margin-top: 30px;
            padding: 10px;
            background-color: #f5f7fa;
            border-top: 1px solid #e5e5e5;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Header Section -->
    <div class="header">
        <h1>Project Blood-Report</h1>
        <p><strong>Organization:</strong> {{ \Illuminate\Support\Str::limit($organization_name, 50, '...') }}</p>
        <p><strong>Description:</strong> {{ \Illuminate\Support\Str::limit($organization_description, 100, '...') }}</p>
    </div>

    <!-- Statistics Section -->
    <div class="section">
        <h2>General Statistics</h2>
        <table class="statistics-table">
            <tr>
                <td class="statistics-cell">
                    <div class="statistics-value">{{ $repositories->count() }}</div>
                    <div class="statistics-description">Total Repositories</div>
                </td>
                <td class="statistics-cell">
                    <div class="statistics-value">{{ $total_milestones }}</div>
                    <div class="statistics-description">Total Milestones</div>
                </td>
                <td class="statistics-cell">
                    <div class="statistics-value">{{ $total_percentage }}%</div>
                    <div class="statistics-description">Progress Percentage</div>
                </td>
            </tr>
            <tr>
                <td class="statistics-cell">
                    <div class="statistics-value">{{ $total_issues }}</div>
                    <div class="statistics-description">Total Issues</div>
                </td>
                <td class="statistics-cell">
                    <div class="statistics-value">{{ $total_issues_open }}</div>
                    <div class="statistics-description">Open Issues</div>
                </td>
                <td class="statistics-cell">
                    <div class="statistics-value">{{ $total_issues_closed }}</div>
                    <div class="statistics-description">Closed Issues</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Top Milestones Section -->
    <div class="section">
        <h2>Top Milestones</h2>
        <div class="content">
            @foreach ($top_milestones as $milestone)
                <div class="milestone">
                    <span class="milestone-title">{{ \Illuminate\Support\Str::limit($milestone->title, 50, '...') }}</span>
                    <div class="progress-bar-container">
                        <div class="progress-bar" style="width: {{ min($milestone->progress, 100) }}%;"></div>
                    </div>
                    <span class="progress-percentage">{{ number_format($milestone->progress, 2) }}%</span>
                </div>
            @endforeach
        </div>
    </div>


    <!-- Sprint Statistics Section -->
    <div class="section">
        <h2>Sprint Statistics</h2>
        <p><strong>Sprint Duration:</strong> {{ $sprintStatistics['sprint_duration_weeks'] }} week(s)</p>
        <p><strong>From:</strong> {{ $sprintStatistics['sprint_start_date'] }}</p>
        <p><strong>To:</strong> {{ $sprintStatistics['sprint_end_date'] }}</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
            @if(isset($sprintStatistics['commits_by_author']) && count($sprintStatistics['commits_by_author']) > 0)
                @foreach ($sprintStatistics['commits_by_author'] as $user)
                    <div class="flex flex-col items-center bg-white p-4 rounded-md border border-other-grey">
                        <div class="text-center">
                            <h3 class="text-lg font-semibold text-primary-blue">{{ $user['author']->name }}</h3>
                            <p class="text-sm text-gray-600"><strong>{{ $user['commit_count'] }}</strong> commits</p>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-center text-gray-600">No commit data available for the selected sprint duration.</p>
            @endif
        </div>

        <div class="mt-6">
            <h3 class="text-xl font-semibold text-primary-blue mb-4">Insights</h3>
            <ul class="list-disc list-inside text-gray-700">
                <li><strong>Total Commits:</strong> {{ $sprintStatistics['total_commits'] }}</li>
                <li><strong>Top Committer:</strong> {{ $sprintStatistics['top_committer'] }} ({{ $sprintStatistics['top_committer_commits'] }} commits)</li>
                <li><strong>Average Commits per User:</strong> {{ $sprintStatistics['average_commits_per_user'] }}</li>
                <li><strong>Total Additions:</strong> {{ $sprintStatistics['total_additions'] }}</li>
                <li><strong>Total Deletions:</strong> {{ $sprintStatistics['total_deletions'] }}</li>
                <li><strong>Total Changed Files:</strong> {{ $sprintStatistics['total_changed_files'] }}</li>
            </ul>
        </div>
    </div>


    <div class="section">
        <h2>Commits and Authors</h2>
        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
            <tr>
                <th style="border: 1px solid #ddd; padding: 8px; background-color: #0D3269; color: white; text-align: left;">Commit Message</th>
                <th style="border: 1px solid #ddd; padding: 8px; background-color: #0D3269; color: white; text-align: left;">Author</th>
                <th style="border: 1px solid #ddd; padding: 8px; background-color: #0D3269; color: white; text-align: left;">Author ID</th>
                <th style="border: 1px solid #ddd; padding: 8px; background-color: #0D3269; color: white; text-align: left;">Committed Date</th>
                <th style="border: 1px solid #ddd; padding: 8px; background-color: #0D3269; color: white; text-align: left;">Commit URL</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($commits_and_users) && count($commits_and_users) > 0)
                @foreach ($commits_and_users as $commit)
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ \Illuminate\Support\Str::limit($commit['commit_message'], 20, '...') }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $commit['author_name'] }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $commit['author_id'] }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ \Carbon\Carbon::parse($commit['committed_date'])->format('d-m-Y H:i') }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">
                            <a href="{{ $commit['commit_url'] }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                View Commit
                            </a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" style="text-align: center; padding: 8px;">No commit data available.</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>All Repository Issues</h2>
        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
            <tr>
                <th style="border: 1px solid #ddd; padding: 8px; background-color: #0D3269; color: white; text-align: left;">Repository</th>
                <th style="border: 1px solid #ddd; padding: 8px; background-color: #0D3269; color: white; text-align: left;">Milestone</th>
                <th style="border: 1px solid #ddd; padding: 8px; background-color: #0D3269; color: white; text-align: left;">Task</th>
                <th style="border: 1px solid #ddd; padding: 8px; background-color: #0D3269; color: white; text-align: left;">Status</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($repositories) && count($repositories) > 0)
                @foreach ($repositories as $repository)
                    @foreach ($repository->milestones as $milestone)
                        @foreach ($milestone->tasks->sortByDesc('closed_at') as $task)
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 8px;">{{ \Illuminate\Support\Str::limit($repository->name, 30, '...') }}</td>
                                <td style="border: 1px solid #ddd; padding: 8px;">{{ \Illuminate\Support\Str::limit($milestone->title, 50, '...') }}</td>
                                <td style="border: 1px solid #ddd; padding: 8px;">{{ \Illuminate\Support\Str::limit($task->title, 50, '...') }}</td>
                                <td style="border: 1px solid #ddd; padding: 8px;">{{ $task->closed_at ? 'Closed' : 'Open' }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                @endforeach
            @else
                <tr>
                    <td colspan="4" style="text-align: center; padding: 8px;">No issues available for the repositories.</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>

    <!-- Footer Section -->
    <div class="footer">
        <p>Generated on {{ $generated_date }}</p>
    </div>
</div>
</body>
</html>
