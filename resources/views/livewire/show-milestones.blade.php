<div>
    <div>
        @foreach($milestones as $milestone)
            <h1 class="font-extrabold text-5xl">
                {{
                    $milestone['title']
                }}
            </h1>

            <h2 class="font-bold">
                Opened Issues
            </h2>

            <ul>
                @foreach($milestone['open_issues']['nodes'] as $issue)
                    <li>
                        {{$issue['title']}}
                    </li>
                @endforeach
            </ul>

            <h2 class="font-bold">
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
