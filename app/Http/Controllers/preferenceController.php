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
        // Get the API client and construct the service object.
        $client = $this->getClient(env('GOOGLE_CALENDARS'));
        //if client is array => it has redirect Url
        //if client is not array -=> client object
        if (is_array($client))
        {
            return Redirect($client['redirect']);
        }
        $service = new Google_Service_Calendar($client);


        // Get list of followed callendars
        $calendarList  = $service->calendarList->listCalendarList();
        $calendars =array();
        while(true) {
            foreach ($calendarList->getItems() as $calendarListEntry) {
                // check if exist & followed or not
                $find = calendarList::where('calendar_id', '=', $calendarListEntry->id)
                                        ->where('user_id', '=', Auth::user()->id)
                                        ->first();
                $checked = false;

                if($find)
                {
                    if($find->follow)
                    {
                        $checked = true;
                    }
                }else {

                    $input = new calendarList();
                    $input->user_id = Auth::user()->id;
                    $input->calendar_id = $calendarListEntry->id;
                    $input->follow = 0;
                    $input->save();
                }

                $calendars[] = [
                                'id' => $calendarListEntry->id,
                                'title' =>  $calendarListEntry->getSummary(),
                                'checked' => $checked,
                            ];
                // $calendars[$calendarListEntry->id] = $calendarListEntry->getSummary();
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

        //if no calendar is selected, the validator won't find calendar
        if (isset($data['calendar'])) {
            $validator = Validator::make($request->all(), [
                'calendar.*' => 'required|max:255',
            ]);
        }else {
            return back();
        }

        foreach ($data['calendar'] as $key => $calendar) {
            $update = calendarList::where('calendar_id', '=', $calendar)
                                    ->where('user_id', '=', Auth::user()->id)
                                    ->first();
            $update->save();
            if ($data['action']=='follow!')
            {
                $update->follow = 1;
            }
            elseif ($data['action']=='unfollow!')
            {
                $update->follow = 0;
            }
            $update->save();
        }

        return redirect()->route('calendars');
    }

    public function getEvents(){
         $client = $this->getClient(env('GOOGLE_EVENTS'));
         if (is_array($client))
         {
             return Redirect($client['redirect']);
         }
         $service = new Google_Service_Calendar($client);

         $calList = Auth::user()->getCalendars; //Calendar ID's ophalen
         if (count($calList) == 0) {
             return redirect()->route('calendars');
         }
         $piece = explode(' ',Carbon::today()); //tijd vandaag
         $timeMin = $piece[0].'T00:00:00Z';
         $piece = explode(' ',Carbon::today()->addWeek()); //een week later
         $timeMax = $piece[0].'T00:00:00Z';
         $parm = ['timeMin' => $timeMin,'timeMax' => $timeMax,];

         $events= array();
         foreach ($calList as $key => $value) { //per calendar
             $items = $service->events->listEvents($value->calendar_id, $parm)->items; //
             foreach ($items as $key => $item) { //item binnen calendar
                 $find = Alarms::where('event_id','=', $item->id)
                                ->where('user_id', '=', Auth::user()->id)
                                ->first();
                if (!$find) {
                    $start         =   new Carbon( $item['modelData']['start']['dateTime']);
                    $end           =   new Carbon( $item['modelData']['end']['dateTime']);
                    $summary       =   $item['summary'];
                    $pieces        =   explode(' ',$start);
                    $startDate     =   $pieces[0];
                    $startTime     =   $pieces[1];
                    $data          =   $item->id . '/' . $value->calendar_id . '/' . $start . '/' . $end.'/'. $summary;
                    $event = [
                        'summary'  => $summary,
                        'start'    => $start, //->format('Y-m-d\TH:i')
                        'end'      => $end,
                        'startDate'=> $startDate,
                        'startTime'=> $startTime,
                        'data'     => $data,
                    ];
                   //  dd($event);
                    $events[]=$event;
                }
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
        // dd($data);
        $validator = Validator::make($request->all(), [
            'event.*' => 'required|unique:alarms,event_id|max:255',
        ]);

        $events =   $data['event'];
        $alarms =   $data['alarm'];
        $dates =   $data['date'];

        foreach ($events as $key => $event) {
            $pieces = explode('/',$event);

            $setAlarm   =   new Alarms();
            $setAlarm->user_id      =   Auth::user()->id;
            $setAlarm->event_id     =   $pieces[0];
            $setAlarm->calendar_id  =   $pieces[1];
            $setAlarm->start        =   $pieces[2];
            $setAlarm->end          =   $pieces[3];
            $setAlarm->summary      =   $pieces[4];
            $setAlarm->alarmTime    =   $alarms[$key];
            $setAlarm->alarmDate    =   $dates[$key];
            $setAlarm->save();
        }
        return Redirect()->route('alarms');
    }

    public function getClient($uri){
        // based on =>
        // https://developers.google.com/google-apps/calendar/quickstart/php#step_3_set_up_the_sample
          $client = new Google_Client();
          $client->setClientId(env('GOOGLE_APP_ID'));
          $client->setClientSecret(env('GOOGLE_APP_SECRET'));
          $client->setRedirectUri($uri);
          $client->setAccessType('offline');
          $client->setApprovalPrompt('force');
          $client->setScopes(array('https://www.googleapis.com/auth/calendar.readonly'));

          // Load previously authorized credentials from a cookie.
          if (isset($_COOKIE['accessToken'])) {
            $accessToken = $_COOKIE['accessToken'];
        } elseif(!isset($_GET['code'])) {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            // can't redirect from here => would send it to function above as $client
            return ['redirect'=>$authUrl];


        }elseif (isset($_GET['code'])) {
            $authCode = $_GET['code'];
            // Exchange authorization code for an access token.
            $accessToken = $client->authenticate($authCode);
            // Store the credentials to cookie.
            dd($accessToken);
            dd($client->getRefreshToken()); //=> opslaan nr db
            setcookie('accessToken', $accessToken, time() + (86400 * 30), "/"); // 86400 = 1 day

          }

          //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        //   Refresh the token if it's expired.
          if ($client->isAccessTokenExpired()) {
            $client->refreshToken($client->getRefreshToken());
            setcookie('accessToken', $client->getAccessToken(), time() + (86400 * 30), "/"); // 86400 = 1 day
          }
          $client->setAccessToken($accessToken);

        //   dd('for return client');
          return $client;
    }
}
