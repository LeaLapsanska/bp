<?php

namespace App\Http\Controllers\API;

use App\Room;
use App\Parameter;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255','unique:rooms'],
            'capacity' => ['required', 'integer'],
        ]);

        $room = new Room;
        $room->name = $request->name;
        $room->capacity = $request->capacity;
        $room->isActive = $request->isActive;
        $room->save();

        if (!empty($request->parameters)) {
            foreach ($request->parameters as $parameter) {
                DB::table('parameter_room')->insert([
                    'room_id' => $room->id,
                    'parameter_id' => $parameter
                ]);
            }
        }

       // $room->events = $room->events;
        $room->parameters = $room->parameters;

        return $room;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $room = Room::find($id);

        if (!$room)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Room does not exist!'
            ], 409);
        }

        if ($room->isActive == 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Room is deactiveted',
            ], 403);
        }


        $room->events = $room->events;
        $room->parameters = $room->parameters;

        return $room;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer'],
        ]);

        $room = Room::find($id);
        $room->name = $request->name;
        if(!empty($request->isActive)){
            $room->isActive = $request->isActive;
        }
        $room->capacity = $request->capacity;
        $room->id = $room->id;

        DB::table('parameter_room')->where('room_id', $id)->delete();

        if (!empty($request->parameters)) {
            foreach ($request->parameters as $parameter) {
                DB::table('parameter_room')->insert([
                    'room_id' => $id,
                    'parameter_id' => $parameter
                ]);
            }
        }

        $room->save();

        $room->events = $room->events;
        $room->parameters = $room->parameters;

        return $room;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $room = Room::find($id);

        $events = DB::table('events')->where('room_id', $id)->get();
        DB::table('parameter_room')->where('room_id', $id)->delete();

        if (count($events)>0)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'events'
            ], 400);
        }

        $room->delete();

        return $room;
    }

    public function activate($id)
    {
        $room = Room::find($id);
        $room->isActive = true;
        $room->save();

        return $room;
    }

    public function deactivate($id)
    {
        $room = Room::find($id);
        $events = DB::table('events')->where('room_id', $room->id)->get();

        if (count($events)>0)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'events'
            ], 400);
        }

        $room->isActive = false;
        $room->save();

        return $room;
    }

    public function search(Request $request){
        $parameters = Parameter::where('name','like','%'.$request->keyword.'%')->get();
        //return $parameters;
		$roomIDs = [];

		foreach ($parameters as $parameter)
			foreach($parameter->rooms as $room)
				$roomIDs[] = $room->id;

		$roomIDs = \array_unique($roomIDs);
		if ($roomIDs != []){
            $rooms = Room::whereIn('id', $roomIDs)
                ->orWhere('name','like','%'.$request->keyword.'%')
                ->orWhere('capacity','like','%'.$request->keyword.'%')->get();
        }
		else{
            $rooms =  Room::orWhere('name','like','%'.$request->keyword.'%')
                ->orWhere('capacity','like','%'.$request->keyword.'%')->get();
        }

		$all = [];
		foreach ($rooms as $room){
		    $r = Room::find($room->id);
		    $r->parameters;
		    $all[] = $r;
        }

		return $all;
    }

    public function all()
    {
        $rooms = Room::all();
        $all = [];
        foreach ($rooms as $room)
        {
            $r = Room::find($room->id);
            $r->parameters;
            $all[] = $r;
        }
        return $all;
    }

    public function getRoom($id){
        $room =  Room::find($id);
        $room->parameters;
        $events = DB::table('events')->where('room_id', $id)->get();
        $room->events = $events;

        return $room;

    }

    public function getActiveRooms(){
        $rooms = Room::all();

        $all = [];
        foreach ($rooms as $room){
            if ($room->isActive == 1){
                $room->parameters;
                $all[] = $room;
            }
        }

        return $all;
    }

    public function calendarForRoom($id){
        $events = DB::table('events')->where('room_id', $id)->get();
        $all = [];
        foreach ($events as $event)
        {
            $all[] = [
                'id' => $event->id,
                'title' => $event->name,
                'start' => $event->start,
                'end' => $event->end,
            ];
        }
        return $all;
    }

    public function getRoomsEvents($id){
        $events = DB::table('events')->where('room_id', $id)->get();

        return $events;
    }
}
