@extends('layouts.app')
@section('headerJs')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        var score = {!! json_encode($score_time) !!};
        var fault = {!! json_encode($fault_time) !!};
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            score.unshift(['Date', 'Score'])
            fault.unshift(['Date', 'Faults'])
            var score_data = google.visualization.arrayToDataTable(score);
            var fault_data = google.visualization.arrayToDataTable(fault);

            var score_options = {
                title: 'Score Comparision',
                curveType: 'function',
                legend: { position: 'bottom' }
            };

            var fault_options = {
                title: 'Fault Comparision',
                curveType: 'function',
                legend: { position: 'bottom' }
            };

            var score_chart = new google.visualization.LineChart(document.getElementById('score'));
            var fault_chart = new google.visualization.LineChart(document.getElementById('fault'));

            score_chart.draw(score_data, score_options);
            fault_chart.draw(fault_data, fault_options);
        }
    </script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Analysis</div>

                <div class="panel-body">
                    <div id="fault" style="width: 100%"></div>
                    <div id="score" style="width: 100%"></div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th scope="col">Session on</th>
                            <th scope="col">Name</th>
                            <th scope="col">Time of travel</th>
                            <th scope="col">Score</th>
                            <th scope="col">Number of Faults</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($events as $event)
                            <tr class="table-primary">
                                <th scope="row">
                                    <a href="{{route('action', $event->id)}}">
                                        {{date('d M Y, H:i',strtotime($event->start_time))}}
                                    </a>
                                </th>
                                <th>{{$event->name}}</th>
                                <td>{{$event->time_diff}} mins</td>
                                <td>{{$event->score}}</td>
                                <td>{{$event->no_of_actions}}</td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection
