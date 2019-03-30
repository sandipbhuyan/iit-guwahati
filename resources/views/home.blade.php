@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
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
                                <td>{{$event->time_diff}}</td>
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
@endsection
