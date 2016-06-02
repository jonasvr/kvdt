<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Google_Client;
use Google_Service_Calendar;
use Cookie;
use Auth;
use App\calendarList;
use Carbon\Carbon;
use App\Alarms;
use App\Refreshtokens;
use App\Http\Requests\SetCalendarRequest;
use App\Http\Requests\SetEventRequest;
use Illuminate\View\Middleware\ErrorBinder;


class preferenceController extends Controller
{
    protected $calendarList;
    protected $alarms;

    public function __construct(calendarList $calList, Alarms $alarms)
    {
        $this->calendarList = $calList;
        $this->alarms = $alarms;
        parent::__construct();
    }

    public function getCalendars()
    {
        // Get the API client and construct the service object.
        $client = $this->getClient(env('GOOGLE_CALENDARS'));
        //if client is array => it has redirect Url
        //if client is not array -=> client object
        if (is_array($client))
        {
            return redirect($client['redirect']);
        }
        $service = new Google_Service_Calendar($client);


        // Get list of followed callendars
        $calendarList  = $service->calendarList->listCalendarList();
        $calendars = $this->listCalendars($calendarList);


       $data = [
           'calendarList' => $calendars,
       ];

       return view('setup.calendar',$data);
    }

    public function getEvents(){
        $client = $this->getClient(env('GOOGLE_EVENTS'));
        if (is_array($client))
        {
            return redirect($client['redirect']);
        }

        $service = new Google_Service_Calendar($client);

        $calList = Auth::user()->getCalendars; //Calendar ID's ophalen
        $events = $this->listEvents($calList,$service);

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

    public function setCalendars(SetCalendarRequest $request){
        $data = $request->all();
        foreach ($data['calendar'] as $key => $calendar) {
            $update = $this->calendarList->GetCalendar($calendar);
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

    public function setEvents(SetEventRequest $request)
    {
        $data = $request->all();
        $this->setAlarms($data);

        return redirect()->route('alarms');
    }

    public function getClient($uri)
    {
        $client = $this->setclient($uri);
        $accessToken = $this->checkAccessToken($client);
        if (!$accessToken)
        {
            if(!isset($_GET['code'])) {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                // can't redirect from here => would send it to function above as $client
                return ['redirect'=>$authUrl];
            }
        }
          //   Refresh the token if it's expired.
        $client = $this->checkRefreshToken($client);
        $client->setAccessToken($accessToken);

        //   dd('for return client');
        return $client;
    }







    /////////////HELPERS///////////////

    public function setAlarm($data)
    {
        $events = $data['event'];
        $alarms = $data['alarm'];
        $dates = $data['date'];

        foreach ($events as $key => $event) {
            $pieces = explode('/',$event);

            $setAlarm = new Alarms();
            $setAlarm->user_id = $this->user_id;
            $setAlarm->event_id = $pieces[0];
            $setAlarm->calendar_id = $pieces[1];
            $setAlarm->start = $pieces[2];
            $setAlarm->end = $pieces[3];
            $setAlarm->summary = $pieces[4];
            $setAlarm->alarmTime = $alarms[$key];
            $setAlarm->alarmDate = $dates[$key];
            $setAlarm->save();
        }
    }

    public function listCalendars($calendarList){
        $calendars =array();
        while(true) {
            foreach ($calendarList->getItems() as $calendarListEntry) {
                // check if exist & followed or not
//                dd($calendarListEntry);
                $find = $this->calendarList->GetCalendar($calendarListEntry->id);
                $checked = false;
//                dd($find);

                if(isset($find->follow)) {
                    $checked = true;
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
            }

            return $calendars;
        }
    }


    public function listEvents($calList,$service)
    {
        $piece = explode(' ',Carbon::today()); //tijd vandaag
        $timeMin = $piece[0].'T00:00:00Z';
        $piece = explode(' ',Carbon::today()->addWeek()); //een week later
        $timeMax = $piece[0].'T00:00:00Z';
        $parm = ['timeMin' => $timeMin,'timeMax' => $timeMax,];

        $events= array();
        foreach ($calList as $key => $value) { //per calendar
            $items = $service->events->listEvents($value->calendar_id, $parm)->items; //
            foreach ($items as $key => $item) { //item binnen calendar
                $find = $this->alarms->GetAlarm($item->id);
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
                    $events[]=$event;
                }
            }
        }
        
        return $events;
    }


    public function setClient($uri)
    {
        // based on =>
        // https://developers.google.com/google-apps/calendar/quickstart/php#step_3_set_up_the_sample
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_APP_ID'));
        $client->setClientSecret(env('GOOGLE_APP_SECRET'));
        $client->setRedirectUri($uri);
        $client->setAccessType('offline');
        //   $client->setApprovalPrompt('force');
        $client->setScopes(array('https://www.googleapis.com/auth/calendar.readonly'));

        return $client;
    }

    public function checkAccessToken($client){
        $accessToken = null;
        // Load previously authorized credentials from a cookie.
        if (isset($_COOKIE['accessToken'])) {
            //   dd("in");
            $accessToken = $_COOKIE['accessToken'];
        } elseif (isset($_GET['code'])) {
            $authCode = $_GET['code'];
            // Exchange authorization code for an access token.
            $accessToken = $client->authenticate($authCode);

            // refreshtoken word 1x meegegeven. enkel wanneer de user voor het eerst toestemming geeft
            if (!Auth::user()->refreshtoken) {
                $user = Auth::user();
                $user->refreshtoken = $client->getRefreshToken();
                $user->save();
            }

            // $client->getRefreshToken(); //=> opslaan nr db
            setcookie('accessToken', $accessToken, time() + (86400 * 30), "/"); // 86400 = 1 day
        }

        return $accessToken;
    }

    public function checkRefreshToken($client)
    {
        if ($client->isAccessTokenExpired())
        {
            $client->refreshToken(Auth::user()->refreshtoken);
            setcookie('accessToken', $client->getAccessToken(), time() + (86400 * 30), "/"); // 86400 = 1 day
        }

        return $client;
    }


}
