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
            $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $event->start_time);
            $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $event->end_time);

            $diff_in_minutes = $to->diffInMinutes($from);
            $event->time_diff = $diff_in_minutes;
        }

        return view('home')->with('events', $events);
    }


    /**
     * @param Request $request
     * @param $id
     *
     */
    public function actionDetails(Request $request, $id)
    {
        $actions = Actions::where('e_id', $id)->get();
        $event = Events::find($id);

        foreach ($actions as $action)
        {
            $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $action->start_time);
            $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $action->end_time);

            $diff_in_minutes = $to->diffInMinutes($from);
            $action->time_diff = $diff_in_minutes;
        }

        return view('action')->with('actions', $actions)
                                    ->with('event', $event);
    }
}
