<?php

namespace App\Http\Controllers\API;

use App\Mail\EventChanged;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Event;
use App\Room;
use App\User;
use JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventInvitation;
use App\Mail\EventCanceled;
use App\Mail\UserRemovedFromEvent;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $room_id = request()->room_id;
        $start_date = request()->start_date;
        $end_date = request()->end_date;
        $invitees = request()->invitees;

        $q = DB::table('events');

        if ($room_id)
            $q->where('room_id', $room_id);

        $start = explode(" ", $start_date);
        if ($start_date)
            $q->where('start', 'LIKE', $start[0] . '%');

        $end = explode(" ", $end_date);
        if ($end_date)
            $q->where('end', 'LIKE', $end[0] . '%');

        if (!empty($invitees)) {
            $filtered_events = [];

            foreach ($q->get() as $event) {
                $event = Event::find($event->id);

                if ($event->users->whereIn('id', $invitees)->count()) {
                    $filtered_events[] = $event;
                }
            }

            return collect($filtered_events);
        }

        return $q->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'start' => 'required',
            'end' => 'required',
            'room_id' => 'required',
            'invitees' => 'required'
        ]);

        $room = Room::find($request->room_id);
        $author = JWTAuth::user();


        // if isActive

        if (!$room->isActive)
            return response()->json([
                'status' => 'error',
                'message' => 'deactivated'
            ], 422);

        // if capacity

        if ($room->capacity < \count($request->invitees))
            return response()->json([
                'status' => 'error',
                'message' => 'capacity'
            ], 422);

        // if collision

//        $collision = Event::where('room_id', $request->room_id)->whereBetween('start', [$request->start, $request->end])->orWhereBetween('end', [$request->start, $request->end])->toSql();
        $collision = Event::where(function ($q) use ($request) {
            $q->whereBetween('start', [$request->start, $request->end])->orWhereBetween('end', [$request->start, $request->end]);
        })->where('room_id', $request->room_id)->get();

        if ($collision->count())
            return response()->json([
                'status' => 'error',
                'message' => 'Collision.',
                'events' => $collision
            ], 409);

        /* $collisionEvents = Event::where('room_id', $request->room_id)->get();
        $collision = [];
        foreach($collisionEvents as $collisionEvent){
            $collision[] = Event::find($collisionEvent->id)->whereBetween('start', [$request->start, $request->end])->orWhereBetween('end', [$request->start, $request->end])->get();
        };

        if($collision != [])
            return response()->json([
                'status' => 'error',
                'message' => 'collision',
                'events' => $collision
			], 422);
			*/
        // if user collision - question: If user is added to some other event at given time, ignore him and skip,
        // or send error via JSON?

        // create event

        //$event = Event::create($request->all());
        $event = new Event();
        $event->name = $request->name;
        $event->user_id = $author->id;
        $event->start = $request->start;
        $event->end = $request->end;
        $event->room_id = $request->room_id;

        $event->save();


        // add users to event

        foreach ($request->invitees as $invitee) {
            DB::table('event_user')->insert([
                'user_id' => $invitee,
                'event_id' => $event->id
            ]);
        }

        $event->users = $event->users;

        // send notification email to all users

        foreach ($event->users as $user)
            Mail::to($user->email)->send(new EventInvitation($event));

        return $event;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $actuallUser = JWTAuth::user();
        $event = Event::find($id);
        $r = DB::table('rooms')->where('id', $event->room_id)->get();
        $event->room = $r;
        $event->users;

        $permission = false;

        foreach ($event->users as $user) {
            if ($user->id == $actuallUser->id)
                $permission = true;
        }

        if ($permission) {
            return $event;
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not invited to this event!'
            ], 401);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // request->canceled - používatelia, ktorí majú byť odstranení
        // request->invitees - pozvaní používatelia

        $request->validate([
            'name' => 'required',
            'start' => 'required',
            'end' => 'required',
            'room_id' => 'required',
            'invitees' => 'required'
        ]);
        $event = Event::find($id);
        $room = Room::find($event->room_id);

        if (!$room->isActive)
            return response()->json([
                'status' => 'error',
                'message' => 'Room is deactivated.'
            ], 422);

        $collision = Event::where(function ($q) use ($request) {
            $q->whereBetween('start', [$request->start, $request->end])->orWhereBetween('end', [$request->start, $request->end]);
        })->where('room_id', $request->room_id)->get();

        if ($collision->count()) {
            if ($collision->count() == 1) {
                if ($collision[0]->id == $id && $collision[0]->room_id == $request->room_id) {
                    // nasiel som sameho seba
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Collision.',
                        'events' => $collision
                    ], 409);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Collision.',
                    'events' => $collision
                ], 409);
            }
        }

        $counter = 0;
        foreach ($event->users as $user)
            $counter++;

        if (!empty($request->invitees)) {
            if ($room->capacity < $counter + \count($request->invitees))
                return response()->json([
                    'status' => 'error',
                    'message' => 'Capacity exceeded.'
                ], 406);
        }


        if (!empty($request->invitees)) {
            foreach ($event->users as $user) {
                $userArray[] = $user->id;
            }

            $invitees = array_diff($request->invitees, $userArray);
            $canceled = array_diff($userArray, $request->invitees);

            foreach ($invitees as $invitee) {
                DB::table('event_user')->insert([
                    'user_id' => $invitee,
                    'event_id' => $id
                ]);
                $user = User::find($invitee);
                Mail::to($user->email)->send(new EventInvitation($event));
            }

            foreach ($canceled as $cancel) {
                DB::table('event_user')->where([['event_id', '=', $id], ['user_id', '=', $cancel]])->delete();
                $user = User::find($cancel);
                Mail::to($user->email)->send(new EventCanceled($event));
            }
        }

        $userArray = [];
        foreach ($event->users as $user) {
            $userArray[] = $user->id;
        }
        $invitees = [];
        $invitees = array_diff($request->invitees, $userArray);
        $withoutInvitees = array_diff($userArray, $invitees);

        foreach ($withoutInvitees as $without){
            $user = User::find($without);
            Mail::to($user->email)->send(new EventChanged($event));
        }

        if (!empty($request->name))
            $event->name = $request->name;
        else
            $event->name = $event->name;

        if (!empty($request->start))
            $event->start = $request->start;
        else
            $event->start = $event->start;

        if (!empty($request->end))
            $event->end = $request->end;
        $event->end = $event->end;

        if (!empty($request->room_id))
            $event->room_id = $request->room_id;
        else
            $event->room_id = $event->room_id;

        $event->save();


        return $event;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::find($id);

        if ($event == []) {
            return response()->json([
                'status' => 'error',
                'message' => 'Event does not exist!'
            ], 409);
        }

        foreach ($event->users as $user) {
            Mail::to($user->email)->send(new EventCanceled($event));
        }

        DB::table('event_user')->where('event_id', $id)->delete();

        $event->delete();

        return $event;
    }

    // Removes user from event

    public function removeFromEvent($id)
    {
        $user = JWTAuth::user();
        $event_id = $id;

        // if(JWTAuth::user()->id != $user_id)
        // {
        // 	return response()->json([
        // 		'status' => 'error',
        // 		'message' => 'Wtf you do?'
        // 	], 403);
        // }

        $user = User::find($user->id);
        $event = Event::find($event_id);

        DB::table('event_user')->where('user_id', $user->id)->where('event_id', $event_id)->delete();

        Mail::to($event->author->email)->send(new UserRemovedFromEvent($event, $user));

        return response()->json([], 204);
    }

    /*public function showAllMyEvents(){
        $room_id = request()->room_id;
        $start_date = request()->start_date;
        $end_date = request()->end_date;

        $user = JWTAuth::user();
       // $my_events = DB::table('event_user')->select('event_id')->where('user_id',$user->id)->get();

      //  return $my_events;

        $q = DB::table('events');

        $filtered_events = [];

        foreach($q->get() as $event)
        {
            $event = Event::find($event->id);

            if($event->users->whereIn('id', $user->id)->count())
            {
                $filtered_events[] = $event;
            }
        }

        $finall_events = [];

            foreach($filtered_events as $filtered_event)
            {
                if(!empty($room_id)){
                    if ($filtered_event->room_id == $room_id)
                    {
                        $finall_events[] = $filtered_event;
                    }
                }

                $date = $filtered_event->start;
                $start = explode(" ", $date);
                if(!empty($start_date)){
                    if ($start[0] == $start_date)
                    {
                        $finall_events[] = $filtered_event;
                    }
                }


                $date_e = $filtered_event->start;
                $end = explode(" ", $date_e);
                if(!empty($end_date)){
                    if ($end[0] == $end_date)
                    {
                        $finall_events[] = $filtered_event;
                    }
                }
            }

        return collect($finall_events);
    }*/

    public function all()
    {
        $events = Event::all();
        $all = [];
        foreach ($events as $event) {
            $e = Event::find($event->id);
            $r = DB::table('rooms')->where('id', $e->room_id)->get();
            $e->users;
            $e->room = $r;
            $all[] = $e;
        }
        return $all;
    }

    public function getEvent($id)
    {
        $event = Event::find($id);
        $r = DB::table('rooms')->where('id', $event->room_id)->get();
        $event->room = $r;
        $event->users;
        return $event;
    }

    public function dataForCalendar()
    {
        $events = Event::all();
        $all = [];
        foreach ($events as $event) {
            $all[] = [
                'id' => $event->id,
                'title' => $event->name,
                'start' => $event->start,
                'end' => $event->end,
                'class' => 'lunch'
            ];
        }
        return $all;
    }

    public function myEventsForCalendar()
    {
        $user = JWTAuth::user();
        // $my_events = DB::table('event_user')->select('event_id')->where('user_id',$user->id)->get();

        //  return $my_events;

        $q = DB::table('events');

        $filtered_events = [];

        foreach ($q->get() as $event) {
            $event = Event::find($event->id);

            if ($event->users->whereIn('id', $user->id)->count()) {
                $filtered_events[] = [
                    'id' => $event->id,
                    'title' => $event->name,
                    'start' => $event->start,
                    'end' => $event->end,
                    'class' => 'lunch'
                ];
            }
        }
        return $filtered_events;
    }

    public function showAllMyEvents()
    {
        $user = JWTAuth::user();
        // $my_events = DB::table('event_user')->select('event_id')->where('user_id',$user->id)->get();

        //  return $my_events;

        $q = DB::table('events');

        $filtered_events = [];

        foreach ($q->get() as $event) {
            $event = Event::find($event->id);
            $r = DB::table('rooms')->where('id', $event->room_id)->get();
            $event->room = $r;

            if ($event->users->whereIn('id', $user->id)->count()) {
                $filtered_events[] = $event;
            }
        }
        return $filtered_events;
    }

    public function myCollisions()
    {
        $user = JWTAuth::user();

        $events = Event::all()->sortBy("start");

        $all = [];
        foreach ($events as $event) {
            if ($event->users->whereIn('id', $user->id))
                $all[] = $event;
        }
        $count = count($all);

        $collisions = [];
        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {

                $start_i = Carbon::parse($all[$i]->start, 'UTC');
                $start_j = Carbon::parse($all[$j]->start, 'UTC');
                $end_i = Carbon::parse($all[$i]->end, 'UTC');
                $end_j = Carbon::parse($all[$j]->end, 'UTC');
                $different = $start_i->diffInMinutes($start_j);
                $duration_i = $start_i->diffInMinutes($end_i);
                // $duration_j = $start_j->diffInMinutes($end_j);


                if ($start_i->eq($start_j) || $duration_i > $different) {
                    $all[$i]->collisions = $all[$i]->collisions + 1;
                }
            }
        };
        for ($i = 0; $i < $count; $i++) {
            if ($all[$i]->collisions != null)
                $collisions[] = $all[$i];

        }


            return $collisions;
    }

    public function nearEvents()
    {
        date_default_timezone_set('Europe/Bratislava');
        $time = date("Y-m-d H:i:s", time());

        $user = JWTAuth::user();

        $events = Event::all();

        $all = [];
        foreach ($events as $event) {
            if ($event->users->whereIn('id', $user->id) && ($event->start > $time))
                $all[] = $event;
        }

        $count = count($all);

       /* if ($count > 5) {
            $finall = [];
            for ($i = 0; $i < 5; $i++) {
                $finall = $all[$i];
            }
            return $finall;
        }*/

        return $all;
    }

    public function search(Request $request) {
        $users = User::where('name','like','%'.$request->keyword.'%')->get();

        $usersIDs = [];

        foreach ($users as $user)
            foreach($user->events as $event)
                $usersIDs[] = $event->id;

        $usersIDs = \array_unique($usersIDs);

        $rooms = Room::where('name','like','%'.$request->keyword.'%')->get();

        $roomIDs = [];

        foreach ($rooms as $room)
            foreach ($room->events as $event)
                $roomIDs[] = $event->id;

            $roomIDs = \array_unique($roomIDs);

            $eventIDs = \array_merge($roomIDs,$usersIDs);
            $eventIDs = \array_unique($eventIDs);

            if ($eventIDs != []){
               $events = Event::whereIn('id',$eventIDs)
                   ->orWhere('name','like','%'.$request->keyword.'%')->get();
            }
            else {
                $events = Event::orWhere('name','like','%'.$request->keyword.'%')->get();
            }

            $all = [];
            foreach ($events as $event){
                $e = Event::find($event->id);
                $e->users;
                $r = DB::table('rooms')->where('id', $e->room_id)->get();
                $e->room = $r;
                $all[] = $e;
            }

            return $all;

    }



}
