<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
        }
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
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            font-size: 20px;
            color: #4a90e2;
            margin-bottom: 10px;
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
        .section .content p {
            margin: 4px 0;
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
        .footer {
            text-align: center;
            font-size: 12px;
            color: #aaa;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Project Report</h1>
    <p>Organization: {{ $organization_name }}</p>
    <p>Description: {{ $organization_description }}</p>
</div>

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

<div class="section">
    <h2>Top Milestones</h2>
    <div class="content">
        <div class="milestones">
            @foreach ($top_milestones as $milestone)
                <div class="milestone">
                    <span>{{ $milestone->name }}</span>
                    <div class="milestone-progress">
                        <div class="progress-bar" style="width: {{ $milestone->progress }}%;"></div>
                    </div>
                    <span>{{ $milestone->progress }}%</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="footer">
    <p>Generated on {{ $generated_date }}</p>
</div>

</body>
</html>
