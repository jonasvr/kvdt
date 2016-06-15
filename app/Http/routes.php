<?php
use App\events\NewUserSignedUp;

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
    Route::post('setalarm', 'ApiController@setAlarm');
    Route::post('emergency', 'ApiController@emergency');
    Route::post('shower', 'ApiController@shower');
});



Route::group(['prefix' => 'preference'], function () {
        Route::get('calendars',         ['as' => 'calendars', 'uses' => 'PreferenceController@getCalendars']);
        Route::post('/setcalendars',    ['as' => 'setCalendars', 'uses' => 'PreferenceController@setCalendars']);
        Route::get('events',            ['as' => 'events', 'uses' => 'PreferenceController@getEvents']);
        Route::post('/setEvents', ['as' => 'setEvents', 'uses' => 'PreferenceController@setEvents']);
        Route::get('test', ['as' => 'test', 'uses' => 'PreferenceController@test']);
});

Route::group(['prefix' => 'alarms'], function () {
    Route::get('/',                     ['as'   =>  'alarms',               'uses'  =>'AlarmController@getAlarms']);
    Route::post('update',               ['as'   =>  'updateAlarm',          'uses'    =>'AlarmController@updateAlarms']);
    Route::get('delete/{alarm_id}',      ['as'   =>  'deleteAlarm',          'uses'=>'AlarmController@deleteAlarm']);

    Route::group(['prefix' => 'emergency'],function(){
        Route::get('/{alarm_id}',  ['as'    =>  'emergency',            'uses' => 'AlarmController@emergency']);
        Route::post('update',       ['as'   =>  'updateEmerg',          'uses'=>'AlarmController@updateEmergency']);
        Route::get('delete/{alarm_id}',      ['as'   =>  'deleteEmerg',          'uses'=>'AlarmController@deleteEmerg']);
    });

});


Route::group(['prefix' => 'profile'], function () {

    Route::get('/', ['as'=>'profile', 'uses'=>'ProfileController@profile']);
    Route::post('/update', ['as'=>'updateProfile', 'uses'=>'ProfileController@update']);
    Route::post('/addDevice', ['as'   =>  'addDevice', 'uses'    =>'ProfileController@addDevice']);

    Route::post('/addKot',  ['as'   =>  'addKot', 'uses'    =>'KotController@addKot']);
    Route::get('accept/{applyUser_id}/{apply_id}', ['as'=>'acceptApply', 'uses'=>'KotController@acceptApply']);
    Route::get('remove/{apply_id}', ['as'=>'removeApply', 'uses'=>'KotController@removeApply']);

});


Route::group(['prefix' => 'numbers'], function(){
    Route::get('/',             ['as' => 'numbers',         'uses'=>'PhoneController@get']);
    Route::post('add',          ['as' => 'addNumber',       'uses'=>'PhoneController@add']);
    Route::get('delete/{id}',   ['as' => 'deleteNumber',    'uses'=>'PhoneController@delete']);
    Route::get('edit/{id}',     ['as' => 'getEditNumber',   'uses'=>'PhoneController@getEdit']);
    Route::post('edit',         ['as' => 'editNumber',      'uses'=>'PhoneController@edit']);

});
Route::group(['prefix' => 'mails'], function(){
    Route::get('/',             ['as' => 'mails',       'uses'=>'MailController@get']);
    Route::post('add',          ['as' => 'addMail',     'uses'=>'MailController@add']);
    Route::get('delete/{id}',   ['as' => 'deleteMail',  'uses'=>'MailController@delete']);
    Route::get('edit/{id}',     ['as' => 'getEditMail', 'uses'=>'MailController@getEdit']);
    Route::post('edit',         ['as' => 'editMail',    'uses'=>'MailController@edit']);

});

Route::group(['prefix' => 'messages'], function(){
    Route::get('/',             ['as' => 'mess',        'uses'=>'MessageController@get']);
    Route::post('add',          ['as' => 'addMess',     'uses'=>'MessageController@add']);
    Route::get('delete/{id}',   ['as' => 'deleteMess',  'uses'=>'MessageController@delete']);
    Route::get('edit/{id}',     ['as' => 'getEditMess', 'uses'=>'MessageController@getEdit']);
    Route::post('edit',         ['as' => 'editMess',    'uses'=>'MessageController@edit']);

});

Route::get('social/login/redirect/{provider}', ['uses' => 'Auth\AuthController@redirectToProvider', 'as' => 'social.login']);
Route::get('social/login/{provider}', 'Auth\AuthController@handleProviderCallback');

Route::auth();

Route::get('/home', 'HomeController@index');
