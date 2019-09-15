<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'API\AuthController@login');
Route::post('forgottenpassword', 'API\UserController@forgottenPassword');


/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::middleware('jwt.auth')->group(function(){

    Route::get('/admin','API\UserController@isAdmin');
    Route::get('/manager','API\UserController@isManager');
    Route::get('/actuall','API\UserController@actuallUser');

    Route::group(['middleware' => ['isAdmin']], function() {
        /*Route::resources([
            'users' => 'API\UserController'
        ]);*/
        Route::post('/users','API\UserController@store');
        Route::get('/user/{id}','API\UserController@getUser');
        Route::put('/users/{id}','API\UserController@update');
        Route::delete('/users/{id}', 'API\UserController@destroy');
        Route::patch('/users/{id}/activate', 'API\UserController@activate');
        Route::patch('/users/{id}/deactivate', 'API\UserController@deactivate');
        Route::post('/searchUser','API\UserController@search');
    });

    Route::group(['middleware' => 'isManager'], function() {
        Route::get('/users','API\UserController@allUsers');
        Route::get('/rooms','API\RoomController@all');
        Route::post('/rooms','API\RoomController@store');
        Route::get('/rooms/{id}', 'API\RoomCOntroller@getRoom');
        Route::put('/rooms/{id}','API\RoomController@update');
        Route::delete('/rooms/{id}','API\RoomController@destroy');
        Route::patch('/rooms/{id}/activate','API\RoomController@activate');
        Route::patch('/rooms/{id}/deactivate','API\RoomController@deactivate');
        Route::get('/roomsEvents/{id}','API\RoomController@getRoomsEvents');
        Route::get('/parameters','API\ParameterController@all');
        Route::post('/parameters','API\ParameterController@store');
        Route::get('/parameters/{id}','API\ParameterController@show');
        Route::put('/parameters/{id}','API\ParameterController@update');
        Route::delete('/parameters/{id}','API\ParameterController@destroy');
        Route::get('/events','API\EventController@all');
        Route::post('/events','API\EventController@store');
        Route::get('/events/{id}','API\EventController@getEvent');
        Route::put('/events/{id}','API\EventController@update');
        Route::delete('/events/{id}','API\EventController@destroy');
        Route::get('/events/all', 'API\EventController@index');
        Route::get('/calendar','API\EventController@dataForCalendar');
        Route::post('/searchEvent','API\EventController@search');
    });

    Route::get('/events/{id}','API\EventController@getEvent');
    Route::get('/calendar/{id}','API\RoomController@calendarForRoom');

    Route::get('/activeRooms','API\RoomController@getActiveRooms');
    Route::get('/rooms/search','API\RoomController@search');
    Route::get('/users/{id}', 'API\UserController@show');
    Route::get('/profile', 'API\UserController@showProfile');
    Route::patch('/profile', 'API\UserController@editProfile');
    Route::get('/rooms/{id}','API\RoomController@show');
    Route::get('/myevents', 'API\EventController@showAllMyEvents');
    Route::get('/myevents/{id}','API\EventController@show');
    Route::get('/mycalendar','API\EventController@myEventsForCalendar');
    Route::delete('/removeFromEvent/{id}', 'API\EventController@removeFromEvent');
    Route::get('/myCollisions','API\EventController@myCollisions');
    Route::get('/nearEvents','API\EventController@nearEvents');
    Route::post('/searchRoom','API\RoomController@search');

    Route::get('logout', 'API\AuthController@logout');

});
