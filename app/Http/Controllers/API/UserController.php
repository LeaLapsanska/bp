<?php

namespace App\Http\Controllers\API;

use App\User;
use function GuzzleHttp\Promise\all;
use http\Message;
use App\Mail\ForgottenPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use JWTAuth;


class UserController extends Controller
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->isAdmin = $request->isAdmin;
        if ($user->isAdmin == 1) {
            $user->isManager = true;
        }
        else {
            $user->isManager =  $request->isManager;
        }
        $user->isActive = $request->isActive;

        $user->save();

        return $user;

        //return $user;

        //return json_encode(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if ($user == [])
        {
            return response()->json([
                'status' => 'error',
                'message' => 'User does not exist!'
            ], 409);
        }

        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required', 'string', 'max:255',
            'email' => 'required|email',
        ]);

        // if ($request->id != '')
        // {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'You can not change your id!'
        //     ], 409);
        // }

        $actuall = JWTAuth::user();

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password != null) {
            $user->password = bcrypt($request->password);
        }
        if ($request->isAdmin == true) {
            $user->isAdmin = $request->isAdmin;
            $user->isManager = true;
        } else {
            $user->isAdmin = $request->isAdmin;
            $user->isManager = $request->isManager;

        }

        if ($actuall->id == $user->id)
        {
            return response()->json([
                "status" => "error",
                "message" => "User can edit yourself in profile!"
            ], 400);
        }
        //aby si nezmenil id
        $user->id = $user->id;

        $user->save();

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $actuall = JWTAuth::user();

        DB::table('event_user')->where('user_id', $id)->delete();

        if ($user->id == $actuall->id)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'User can not delete yourself!',
                'errors' => 'User can not delete yourself!'
            ], 409);
        }

        $user->delete();

        return $user;
    }

    public function showProfile()
    {
        $user = JWTAuth::user();
        $id = $user->id;
        $user = User::find($id);

        return $user;
    }

    public function editProfile(Request $request)
    {
        $request->validate([
            'name' => 'required', 'string', 'max:255',
            'email' => 'required|email',
            //'password' => 'string', 'min:4',
        ]);

        $user = JWTAuth::user();
        $id = $user->id;
        $user = User::find($id);

        /*if ($request->id != [] || $request->isAdmin != [] || $request->isActive != [])
        {
            return response()->json([
                'status' => 'error',
                'message' => 'You can not change your id or role!'
            ], 409);
        }*/

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password != null){
            $user->password = bcrypt($request->password);
        }
        //aby nemenil to čo nemôže
        $user->id = $user->id;
        $user->isAdmin = $user->isAdmin;
        $user->isActive = $user->isActive;
        $user->isManager = $user->isManager;
        $user->save();

        return $user;
    }

    public function forgottenPassword(Request $request)
    {
       $request->validate([
            'email' => 'required|email',
        ]);

       $user = User::where('email', $request->email)->first();


        if ($user == [])
        {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not registered!'
            ], 401);
        }else
            {
                $random_password = str_random(8);
                $user->password = bcrypt($random_password);
                $user->save();
                //MJ7ZoF9o
                Mail::to($request->email)->send(new ForgottenPassword($random_password));
                return $random_password;
            }
    }

    public function activate($id)
    {
        $user = User::find($id);
        if ($user->isAdmin == 1)
            return response()->json([
                'status' => 'error',
                'message' => 'Logged user can not activate yourslef!'
            ], 400);
        $user->isActive = true;
        $user->save();

        return $user;
    }

    public function deactivate($id)
    {
        $user = User::find($id);
        if ($user->isAdmin == 1)
            return response()->json([
                'status' => 'error',
                'message' => 'Logged user can not deactivate yourself!'
            ], 400);
        $user->isActive = true;
        $user->isActive = false;
        $user->save();

        return $user;

    }

    public function allUsers(){
        $users = User::All();
        return $users;
    }

    public function getUser($id){
        $user = User::find($id);
        return $user;
    }

    public function isAdmin(){
        $user = JWTAuth::user();

        if ($user->isAdmin == 1) return response()->json([
            'isAdmin' => true
        ], 200);
        else return response()->json([
            'isAdmin' => false
        ], 200);
    }

    public function isManager(){
        $user = JWTAuth::user();

        if ($user->isManager == 1) return response()->json([
            'isManager' => true
        ], 200);
        else return response()->json([
            'isManager' => false
        ], 200);

    }

    public function actuallUser(){
        $user = JWTAuth::user();

        return $user;
    }

    public function search(Request $request){
        return User::orWhere('name','like','%'.$request->keyword.'%')
            ->orWhere('email','like','%'.$request->keyword.'%')->get();
    }
}
