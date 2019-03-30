<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events;
use Illuminate\Support\Facades\Auth;

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
}
