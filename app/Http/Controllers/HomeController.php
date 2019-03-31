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

        $score_time = [];
        $faults_time = [];

        foreach ($events as $event) {
            $event->time_diff = $this->calculateTimeDiff($event->start_time, $event->end_time);
            array_push($score_time, [date('d-m-Y',strtotime($event->start_time)),$event->score]);
            array_push($faults_time,[date('d-m-Y',strtotime($event->start_time)),$event->no_of_actions]);
        }

        return view('home')->with('events', $events)
                                ->with('score_time',$score_time)
                                ->with('fault_time',$faults_time);
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
        $mob = 0;
        $drowsy = 0;
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
            if($action->type_of_action == 'using_mobile')
            {
                $mob += $action->time_diff;
            }
            if($action->type_of_action == 'drowsing')
            {
                $drowsy += $action->time_diff;
            }
        }
        $temp = [$good,$back,$mob,$drowsy];
        $key = array_keys($temp,max($temp));
        if($key[0] == 0) {
            $tip = 'Your driving skill is improving';
        }
        else if($key[0] == 1) {
            $tip = 'You were very much distracted from the road. Pay attention a little more on the road';
        }
        else if($key[0] == 2) {
            $tip = 'You were using the phone very much';
        }
        else if($key[0] == 3) {
            $tip = 'Make sure you get proper sleep';
        }

        $pi_chart = [
            ['Good Driving', $good],
            ['Looking Back', $back],
            ['Using in phone', $mob],
            ['Falling sleep', $drowsy]
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
