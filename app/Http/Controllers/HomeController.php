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
        $good = 0;
        $back = 0;
        $text = 0;
        $call = 0;
        $tip = '';
        foreach ($actions as $action)
        {
            $action->time_diff = $this->calculateTimeDiff($action->start_time, $action->end_time);
            if($action->type_of_action == 'good')
            {
                $good += $action->time_diff;
            }
            if($action->type_of_action == 'back')
            {
                $back  += $action->time_diff;
            }
            if($action->type_of_action == 'call')
            {
                $call += $action->time_diff;
            }
            if($action->type_of_action == 'text')
            {
                $text += $action->time_diff;
            }
        }
        $temp = [$good,$back,$text,$call];
        $key = array_keys($temp,max($temp));
        if($key[0] == 0) {
            $tip = 'Your driving skill is improving';
        }
        else if($key[0] == 1) {
            $tip = 'You are verymuch distracted from the road. Pay attention a little more to the road';
        }
        else if($key[0] == 2 || $key == 3) {
            $tip = 'You are using the phone very much';
        }

        $pi_chart = [
            ['Good Driving', $good],
            ['Looking Back', $back],
            ['Texting in phone', $text],
            ['Spending in Call', $call]
        ];
        return view('action')->with('actions', $actions)
                                    ->with('event', $event)
                                    ->with('pi_chart',$pi_chart)
                                    ->with('tip', $tip)
                                    ->with('key', $key[0]);
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
        $to = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $start);
        $from = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $end);
        return $to->diffInMinutes($from);
    }
}
