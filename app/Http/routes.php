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


Route::group(['prefix' => 'api','middleware' => 'api'], function () {
    Route::post('setalarm', 'AlarmController@setAlarm');
});

Route::group(['middleware' => 'web'], function () {


    Route::group(['prefix' => 'preference'], function () {
            Route::get('calendars', ['as' => 'calendars', 'uses' => 'preferenceController@getCalendars']);
            Route::post('/setcalendars', ['as' => 'setCalendars', 'uses' => 'preferenceController@setCalendars']);
            Route::get('events', ['as' => 'events', 'uses' => 'preferenceController@getEvents']);
            Route::post('/setEvents', ['as' => 'setEvents', 'uses' => 'preferenceController@setEvents']);
            Route::get('test', ['as' => 'test', 'uses' => 'preferenceController@test']);
    });

    Route::group(['prefix' => 'alarms'], function () {
        Route::get('get',   ['as'   =>  'alarms',        'uses'  =>'AlarmController@getAlarms']);
        Route::post('update',  ['as'   =>  'updateAlarm', 'uses'    =>'AlarmController@updateAlarms']);
    });

    Route::group(['prefix' => 'devices'], function () {
        Route::get('/',   ['as'   =>  'devices',  function(){
            return view('setup.device');
        }]);
        Route::post('add',  ['as'   =>  'addDevice', 'uses'    =>'DeviceController@addDevice']);
    });
    // Route::get('alarms', ['as'=>'alarms', 'uses'=>'AlarmController@getAlarms']);
    // Route::post('', ['as' => 'setEvents', 'uses' => 'preferenceController@setEvents']);

    Route::get('social/login/redirect/{provider}', ['uses' => 'Auth\AuthController@redirectToProvider', 'as' => 'social.login']);
    Route::get('social/login/{provider}', 'Auth\AuthController@handleProviderCallback');

    Route::auth();

    Route::get('/home', 'HomeController@index');
});
