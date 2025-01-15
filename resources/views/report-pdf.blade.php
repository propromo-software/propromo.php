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
            background-color: #007BFF;
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
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .section h2 {
            font-size: 22px;
            color: #007BFF;
            margin-bottom: 15px;
            border-bottom: 2px solid #007BFF;
            padding-bottom: 5px;
        }

        .section p {
            font-size: 14px;
            color: #555;
        }

        /* Content Styling */
        .content {
            line-height: 1.6;
        }

        /* Milestone Styling */
        .milestone {
            padding: 10px 0;
            border-bottom: 1px solid #e5e5e5;
            display: flex;
            flex-direction: column;
            gap: 5px;
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
            background-color: #007BFF;
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
            color: #007BFF;
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
            @foreach ($top_milestones as $milestone)
                <div class="milestone">
                    <span class="milestone-title">{{ $milestone->title }}</span>
                    <div class="progress-bar-container">
                        <div class="progress-bar" style="width: {{ min($milestone->progress, 100) }}%;"></div>
                    </div>
                    <span class="progress-percentage">{{ number_format($milestone->progress, 2) }}%</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Users and Commit Counts Section -->
    <div class="section">
        <h2>Users and Sprint-Commit Counts</h2>
        <div class="content">
            @foreach ($commitUsers as $user)
                <div class="user-commit">
                    <strong>{{ $user->name }}</strong>: {{ $user->commit_count }} commits
                </div>
            @endforeach
        </div>
    </div>

    <!-- Footer Section -->
    <div class="footer">
        <p>Generated on {{ $generated_date }}</p>
    </div>
</div>
</body>
</html>
