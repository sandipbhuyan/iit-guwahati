<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Events;
use App\Actions;

class GlobalAPI extends Controller
{
    /**
     * Store the  Event Details
     *
     * Store the session details and action details here
     *
     * @param Request $request
     *
     * @return Response
     */
    public function eventStore(Request $request) {
        $event = new Events;

        $event->name = $request->main['name'];
        $event->start_time = $request->main['start_time'];
        $event->end_time = $request->main['end_time'];
        $event->no_of_actions = $request->main['no_of_action'];
        $event->u_id = $request->main['u_id'];
        $event->score = $request->main['score'];
        $event->save();

        foreach ($request->details as $detail)
        {
            $action = new Actions;
            $action->e_id = $event->id;
            $action->start_time = $detail['start_time'];
            $action->end_time = $detail['end_time'];
            $action->score = $detail['score'];
            $action->type_of_action =$detail['type_of_action'];
            $action->save();
        }

        return response('success', 200);
    }
}
