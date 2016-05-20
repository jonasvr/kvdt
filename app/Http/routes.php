<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', 'TestController@Test');
Route::get('/test2', 'TestController@Test2');


Route::group(['middleware' => 'web'], function () {
    Route::get('/test3', 'TestController@Test3');

    Route::group(['prefix' => 'preference'], function () {
            Route::get('calendars', ['as' => 'calendars', 'uses' => 'preferenceController@getCalendars']);
            Route::post('/setcalendars', ['as' => 'setCalendars', 'uses' => 'preferenceController@setCalendars']);
            Route::get('events', ['as' => 'events', 'uses' => 'preferenceController@getEvents']);
            Route::post('/setEvents', ['as' => 'setEvents', 'uses' => 'preferenceController@setEvents']);
            Route::get('test', ['as' => 'test', 'uses' => 'preferenceController@test']);

    });

    Route::get('social/login/redirect/{provider}', ['uses' => 'Auth\AuthController@redirectToProvider', 'as' => 'social.login']);
    Route::get('social/login/{provider}', 'Auth\AuthController@handleProviderCallback');

    Route::auth();

    Route::get('/home', 'HomeController@index');
});
