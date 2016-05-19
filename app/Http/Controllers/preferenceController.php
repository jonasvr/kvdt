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
use Carbon\Carbon;
use App\Alarms;
use Session;

class preferenceController extends Controller
{
    public function getCalendars(){
        $client = $this->getClient('http://kvdt.dev/preference/calendars');
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

    public function setCalendars(Request $request){
        $data = $request->all();

        $validator = Validator::make($request->all(), [
            'calendar.*' => 'required|max:255',
        ]);

        foreach ($data['calendar'] as $key => $calendar) {
            $input = new calendarList();
            $input->user_id = Auth::user()->id;
            $input->calendar_id = $calendar;
            $input->follow = 1;
            $input->save();
        }
        return redirect()->route('calendars');
    }

    public function getEvents(){
        // 'http://kvdt.dev/preference/events'
        $client = $this->getClient('http://kvdt.dev/preference/events');
         $service = new Google_Service_Calendar($client);

         $calList = Auth::user()->getCalendars; //Calendar ID's ophalen
         $piece = explode(' ',Carbon::today()); //tijd vandaag
         $timeMin = $piece[0].'T00:00:00Z';
         $piece = explode(' ',Carbon::today()->addWeek()); //een week later
         $timeMax = $piece[0].'T00:00:00Z';
         $parm = ['timeMin' => $timeMin,'timeMax' => $timeMax,];

         $events= array();
         foreach ($calList as $key => $value) { //per calendar
             $items = $service->events->listEvents($value->calendar_id, $parm)->items; //
             foreach ($items as $key => $item) { //item binnen calendar

                 $start =   new Carbon( $item['modelData']['start']['dateTime']);
                 $end   =   new Carbon( $item['modelData']['end']['dateTime']);
                 $pieces=   explode(' ',$start);
                 $min   =   $pieces[1];
                 $data  =   $item->id . '/' . $value->calendar_id . '/' . $start . '/' . $end;
                 $event = [
                     'summary'  => $item['summary'],
                     'start'    => $start->format('Y-m-d\TH:i'),
                     'end'      => $end,
                     'min'      => $min,
                     'data'     => $data,
                 ];
                 $events[]=$event;
             }
         }
            //sort by start date
            $start = array();
            foreach ($events as $key => $row)
            {
                $start[$key] = $row['start'];
            }
         array_multisort($start, SORT_ASC, $events);
         $data = ['events' => $events];
         return view('setup.events',$data);
    }

    public function setEvents(Request $request){
        $data = $request->all();

        $validator = Validator::make($request->all(), [
            'event.*' => 'required|unique:alarms,event_id|max:255',
        ]);

        $events =   $data['event'];
        $alarms =   $data['alarm'];

        foreach ($events as $key => $event) {
            $pieces = explode('/',$event);

            $setAlarm   =   new Alarms();
            $setAlarm->event_id     =   $pieces[0];
            $setAlarm->calendar_id  =   $pieces[1];
            $setAlarm->start        =   $pieces[2];
            $setAlarm->end          =   $pieces[3];
            $setAlarm->alarm        =   $alarms[$key];
            $setAlarm->save();
        }
    }


    public function getClient($uri){
        // based on =>
        // https://developers.google.com/google-apps/calendar/quickstart/php#step_3_set_up_the_sample
          $client = new Google_Client();
          $client->setClientId(env('GOOGLE_APP_ID'));
          $client->setClientSecret(env('GOOGLE_APP_SECRET'));
          $client->setRedirectUri($uri);
          $client->setAccessType('offline');
          $client->setScopes(array('https://www.googleapis.com/auth/calendar.readonly'));

          // Load previously authorized credentials from a cookie.
          if (isset($_COOKIE['accessToken'])) {
            $accessToken = $_COOKIE['accessToken'];
        } elseif(!isset($_GET['code'])) {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            return Redirect($authUrl);
        }elseif (isset($_GET['code'])) {
            $authCode = $_GET['code'];

            // Exchange authorization code for an access token.
            $accessToken = $client->authenticate($authCode);

            // Store the credentials to cookie.
            setcookie('accessToken', $accessToken, time() + (86400 * 30), "/"); // 86400 = 1 day
          }

          $client->setAccessToken($accessToken);

          // Refresh the token if it's expired.
          if ($client->isAccessTokenExpired()) {
            $client->refreshToken($client->getRefreshToken());
            setcookie('accessToken', $client->getAccessToken(), time() + (86400 * 30), "/"); // 86400 = 1 day
          }
          return $client;
    }
}
