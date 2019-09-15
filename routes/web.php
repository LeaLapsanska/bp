<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (){
    return view('welcome');
});

//if (env('APP_DEBUG') == true)
    Route::any('adminer', '\Miroc\LaravelAdminer\AdminerAutologinController@index');
// vzdy pred commitom zakomentovat aby sa nik nedostal do adminera

/*Route::group(['middleware' => ['admin']], function() {
    Route::post('create_user','App\UserController@createUser');
});*/
