@extends('layouts.app')
@section('headerJs')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var temp = {!! json_encode($pi_chart) !!};
            temp.unshift(['Type', 'Amount of Time'])
            var data = google.visualization.arrayToDataTable(temp);

            var options = {
                title: 'Actions'
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

            chart.draw(data, options);
        }
    </script>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Action for</div>

                    <div class="panel-body">
                        <h4 class="card-title">Driving session on {{date('d M Y, H:i',strtotime($event->start_time))}}</h4>
                        <p class="card-text">Total Number of Actions: {{count($actions)}}</p>
                        <a href="{{route('home')}}">Go Back</a>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Action</div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th scope="col">Action Type</th>
                                <th scope="col">Time of occurance</th>
                                <th scope="col">Score</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($actions as $action)
                                <tr class="table-primary">
                                    <th scope="row">{{$action->type_of_action}}</th>
                                    <td>{{$action->time_diff}} mins</td>
                                    <td>{{$event->score}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Pichart for action</div>

                    <div class="panel-body">
                        <div id="piechart" style="width: 100%; height: 100%;"></div>
                        <p class="{{ $key == 0 ? 'text-success': 'text-danger' }} col-md-offset-2"><b>{{$tip}}</b></p>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

