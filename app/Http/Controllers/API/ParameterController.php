<?php

namespace App\Http\Controllers\API;

use App\Parameter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Illuminate\Support\Facades\DB;


class ParameterController extends Controller
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
            'name' => ['required', 'string', 'max:255','unique:parameters'],
        ]);

        $parameter = new Parameter;

        $parameter->name = $request->name;
        $parameter->save();

        return $parameter;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $parameter = Parameter::find($id);

        if (!$parameter)
        {
            return response()->json([
                "status" => "error",
                "message" => "Parameter does not exist!"
        ], 409);
        }

        return $parameter;
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
        ]);
        $parameter = Parameter::find($id);

        $parameter->name = $request->name;
        $parameter->save();

        return $parameter;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $parameter = Parameter::find($id);

        DB::table('parameter_room')->where('parameter_id', $id)->delete();

        $parameter->delete();

        return $parameter;

    }

    public function all(){
        $parameters = Parameter::all();
        return $parameters;
    }
}
