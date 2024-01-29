<div>
    <div class="prose">
        @foreach($milestones as $milestone)
            <h1>
                {{
                    $milestone['title']
                }}
            </h1>

            <h2>
                Opened Issues
            </h2>

            <ul>
                @foreach($milestone['open_issues']['nodes'] as $issue)
                    <li>
                        {{$issue['title']}}
                    </li>
                @endforeach
            </ul>

            <h2>
                Closed Issues
            </h2>

            <ul>
                @foreach($milestone['closed_issues']['nodes'] as $issue)
                    <li>
                        {{$issue['title']}}
                    </li>
                @endforeach
            </ul>

            <br>
        @endforeach
    </div>
</div>
