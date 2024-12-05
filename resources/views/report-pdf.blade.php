<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        /* Header styling */
        .header {
            text-align: center;
            border-bottom: 2px solid #4a90e2;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            color: #4a90e2;
        }

        .header p {
            font-size: 16px;
            color: #555;
        }

        /* Section styling */
        .section {
            margin-bottom: 20px;
        }

        .section h2 {
            font-size: 20px;
            color: #4a90e2;
            margin-bottom: 10px;
            text-align: center;
        }

        .section p {
            font-size: 14px;
            color: #555;
            margin: 5px 0;
        }

        .section .content {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .milestones {
            margin: 10px 0;
        }

        .milestone {
            padding: 5px 0;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
        }

        .milestone:last-child {
            border-bottom: none;
        }

        /* Progress bar styling */
        .milestone-progress {
            width: 50%;
            height: 6px;
            background-color: #eee;
            margin-top: 5px;
            border-radius: 3px;
            position: relative;
        }

        .milestone-progress .progress-bar {
            height: 6px;
            background-color: #4a90e2;
            border-radius: 3px;
        }

        /* Footer styling */
        .footer {
            text-align: center;
            font-size: 12px;
            color: #aaa;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            margin-top: 20px;
        }

        /* Users and Commit Counts section */
        .users-commits {
            margin-top: 20px;
        }

        .user-commit {
            margin-bottom: 5px;
        }

        .user-commit strong {
            color: #4a90e2;
        }

        /* Milestones progress bar */
        .progress-bar-container {
            width: 100%;
            background-color: #f0f0f0;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 5px;
        }

        .progress-bar {
            height: 10px;
            border-radius: 10px;
            background-color: #4a90e2;
        }

        .milestone-title {
            font-size: 14px;
            font-weight: 500;
            color: #555;
        }

        .milestone-progress-container {
            margin-top: 10px;
        }

    </style>
</head>
<body>

<!-- Header Section -->
<div class="header">
    <h1>Project Report</h1>
    <p><strong>Organization:</strong> {{ $organization_name }}</p>
    <p><strong>Description:</strong> {{ $organization_description }}</p>
</div>

<!-- Statistics Section -->
<div class="section">
    <h2>Statistics</h2>
    <div class="content">
        <p><strong>Total Issues:</strong> {{ $total_issues }}</p>
        <p><strong>Open Issues:</strong> {{ $total_issues_open }}</p>
        <p><strong>Closed Issues:</strong> {{ $total_issues_closed }}</p>
        <p><strong>Total Milestones:</strong> {{ $total_milestones }}</p>
        <p><strong>Progress Percentage:</strong> {{ $total_percentage }}%</p>
    </div>
</div>

<!-- Top Milestones Section -->
<div class="section">
    <h2>Top Milestones</h2>
    <div class="content">
        <div class="milestones">
            @foreach ($top_milestones as $milestone)
                <div class="milestone">
                    <span class="milestone-title">{{ $milestone->title }}</span>
                    <div class="milestone-progress-container">
                        <div class="progress-bar-container">
                            <div class="progress-bar" style="width: {{ min($milestone->progress, 100) }}%;"></div>
                        </div>
                    </div>
                    <span>{{ number_format($milestone->progress, 2) }}%</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="section users-commits">
    <h2>Users and Sprint-Commit Counts</h2>
    <div class="content">
        @foreach ($commitUsers as $user)
            <div class="user-commit">
                <strong>{{ $user-> }}</strong>: {{ $user->commit_count }} commits
            </div>
        @endforeach
    </div>
</div>


<div class="footer">
    <p>Generated on {{ $generated_date }}</p>
</div>

</body>
</html>
