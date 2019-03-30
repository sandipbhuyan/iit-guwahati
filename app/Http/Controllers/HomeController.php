<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events;
use Illuminate\Support\Facades\Auth;
use App\Actions;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Events::where('u_id',Auth::user()->id)->get();

        foreach ($events as $event) {
            $event->time_diff = $this->calculateTimeDiff($event->start_time, $event->end_time);
        }

        return view('home')->with('events', $events);
    }


    /**
     * Action details
     *
     * Send Action details of a perticular event and visuzlization values
     *
     * @param Request $request
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function actionDetails(Request $request, $id)
    {
        $actions = Actions::where('e_id', $id)->get();
        $event = Events::find($id);

        foreach ($actions as $action)
        {
            $action->time_diff = $this->calculateTimeDiff($action->start_time, $action->end_time);
            var_dump($action->time_diff);
        }

        return view('action')->with('actions', $actions)
                                    ->with('event', $event);
    }

    /**
     * Calculate Time Diff
     *
     * @param $start
     * @param $end
     *
     * @return int
     */
    private function calculateTimeDiff($start, $end)
    {
        $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $start);
        $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $end);
        var_dump($start);
        var_dump($end);
        return $to->diffInMinutes($from);
    }
}
