@extends('layouts.app')

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
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
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
                                    <td>{{$action->time_diff}}</td>
                                    <td>{{$event->score}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
@endsection
