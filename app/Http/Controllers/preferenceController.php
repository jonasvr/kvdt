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
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_APP_ID'));
        $client->setClientSecret(env('GOOGLE_APP_SECRET'));
        $client->setRedirectUri('http://kvdt.dev/preference/events');
        $client->setAccessType('offline');   // Gets us our refreshtoken
        $client->setScopes(array('https://www.googleapis.com/auth/calendar.readonly'));


          // Step 2: The user accepted your access now you need to exchange it.
         $code = Session::get('code');
         if (isset($_GET['code']) && $code==null) {
          	$client->authenticate($_GET['code']);
          	$token = $client->getAccessToken();
            Session::put('code', $_GET['code']);
            // echo $client->getAccessToken();
            // dd();
        }else {
            Session::put('code', null);
        }

         if (isset($token)) {

             $calList = Auth::user()->getCalendars; //Calendar ID's ophalen

             $client->setAccessToken($token);
             $service = new Google_Service_Calendar($client);
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

         // Step 1:  The user has not authenticated we give them a link to login
         if (!isset($token)) {
             $authUrl = $client->createAuthUrl();
             return Redirect($authUrl);
         }
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


//  $cookie = Cookie::make('name', 'test', 60);
// $cookie_name = "user";
// $cookie_value = "John Doe";
// setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
// if(isset($_COOKIE[$cookie_name])) {
//     echo "Cookie '" . $cookie_name . "' is set!<br>";
//     echo "Value is: " . $_COOKIE[$cookie_name];
// }
    public function getClient(){

          $client = new Google_Client();
          $client->setClientId(env('GOOGLE_APP_ID'));
          $client->setClientSecret(env('GOOGLE_APP_SECRET'));
          $client->setRedirectUri('http://kvdt.dev/preference/test');
          $client->setAccessType('offline');
          $client->setScopes(array('https://www.googleapis.com/auth/calendar.readonly'));

          // Load previously authorized credentials from a file.
          if (isset($_COOKIE['accessToken'])) {
            //   dd('token set');
            $accessToken = $_COOKIE['accessToken'];
        } elseif(!isset($_GET['code'])) {
            // dd('no code');
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            // dd($authUrl);
            return Redirect($authUrl);
        }elseif (isset($_GET['code'])) {
            $authCode = $_GET['code'];

            // Exchange authorization code for an access token.
            $accessToken = $client->authenticate($authCode);

            // Store the credentials to disk.
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

    public function test(){
        // Get the API client and construct the service object.
        setcookie('accessToken', "", time() - 3600);
        $client = $this->getClient();
        // dd($client);
        $service = new Google_Service_Calendar($client);

        // Print the next 10 events on the user's calendar.
        $calendarId = 'primary';
        $optParams = array(
          'maxResults' => 10,
          'orderBy' => 'startTime',
          'singleEvents' => TRUE,
          'timeMin' => date('c'),
        );
        $results = $service->events->listEvents($calendarId, $optParams);

        if (count($results->getItems()) == 0) {
          print "No upcoming events found.\n";
        } else {
          print "Upcoming events:\n";
          foreach ($results->getItems() as $event) {
            $start = $event->start->dateTime;
            if (empty($start)) {
              $start = $event->start->date;
            }
            printf("%s (%s)\n", $event->getSummary(), $start);
          }
        }
    }
}
