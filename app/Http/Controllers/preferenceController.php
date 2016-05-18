<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Google_Client;
use Google_Service_Calendar;
use Cookie;
use Auth;
use Validator;
use App\calendarList;

class preferenceController extends Controller
{
    public function getCalendars(){
       $client = new Google_Client();
       $client->setClientId(env('GOOGLE_APP_ID'));
       $client->setClientSecret(env('GOOGLE_APP_SECRET'));
       $client->setRedirectUri('http://kvdt.dev/preference/calendars');
       $client->setAccessType('offline');   // Gets us our refreshtoken
       $client->setScopes(array('https://www.googleapis.com/auth/calendar.readonly'));


         // Step 2: The user accepted your access now you need to exchange it.
        if (isset($_GET['code'])) {
         	$client->authenticate($_GET['code']);
         	$token = $client->getAccessToken();
        }

        if (isset($token)) {
            $client->setAccessToken($token);
            $service = new Google_Service_Calendar($client);
            $calendarList  = $service->calendarList->listCalendarList();;

            $calendars =array();
            while(true) {
                foreach ($calendarList->getItems() as $calendarListEntry) {
                    $calendars[$calendarListEntry->id] = $calendarListEntry->getSummary();
                }
                $pageToken = $calendarList->getNextPageToken();
               if ($pageToken) {
                    $optParams = array('pageToken' => $pageToken);
                    $calendarList = $service->calendarList->listCalendarList($optParams);
               } else {
                    break;
               }
           }

           $data = [
               'calendarList' => $calendars,
           ];
           return view('setup.calendar',$data);
       }

        // Step 1:  The user has not authenticated we give them a link to login
        if (!isset($token)) {
            $authUrl = $client->createAuthUrl();
            return Redirect($authUrl);
        }
    }

    public function setCalendars(Request $request){
        $data = $request->all();

        $validator = Validator::make($request->all(), [
            'calendar.*' => 'required|unique:posts|max:255',
        ]);

        foreach ($data['calendar'] as $key => $calendar) {
            $input = new calendarList();
            $input->user_id = Auth::user()->id;
            $input->calendarId = $calendar;
            $input->follow = 1;
            $input->save();
        }
    }

    public function getEvents(){
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_APP_ID'));
        $client->setClientSecret(env('GOOGLE_APP_SECRET'));
        $client->setRedirectUri('http://kvdt.dev/preference/events');
        $client->setAccessType('offline');   // Gets us our refreshtoken
        $client->setScopes(array('https://www.googleapis.com/auth/calendar.readonly'));


          // Step 2: The user accepted your access now you need to exchange it.
         if (isset($_GET['code'])) {
          	$client->authenticate($_GET['code']);
          	$token = $client->getAccessToken();
         }

         if (isset($token)) {
             $client->setAccessToken($token);
             $service = new Google_Service_Calendar($client);
             $calendarList  = $service->calendarList->listCalendarList();
             dd($calendarList)
        }

         // Step 1:  The user has not authenticated we give them a link to login
         if (!isset($token)) {
             $authUrl = $client->createAuthUrl();
             return Redirect($authUrl);
         }
    }
}
