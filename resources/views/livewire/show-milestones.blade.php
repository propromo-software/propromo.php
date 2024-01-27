<div>
    <div>
        @foreach($milestones as $milestone)
            <h1>
                {{
                    $milestone['title']
                }}
            </h1>
                <ul>
            @foreach($milestone['open_issues']['nodes'] as $issue)
                <li>
                    {{$issue['title']}}
                </li>
            @endforeach
                </ul>
            <br>
        @endforeach
    </div>
</div>
