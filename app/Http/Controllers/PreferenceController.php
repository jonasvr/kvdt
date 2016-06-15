<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Google_Client;
use Google_Service_Calendar;
use Cookie;
use Auth;
use App\calendarList;
use App\Alarms;
use App\Events;
use Carbon\Carbon;
use App\Refreshtokens;
use App\Http\Requests\SetCalendarRequest;
use App\Http\Requests\SetEventRequest;
use Illuminate\View\Middleware\ErrorBinder;


class PreferenceController extends Controller
{
    /**
     * @var calendarList
     * @var Alarms
     * @var Events
     */
    protected $calendarList;
    protected $alarms;
    protected $events;

    /**
     * PreferenceController constructor.
     * @param calendarList $calList
     * @param Alarms $alarms
     */
    public function __construct(calendarList $calList, Alarms $alarms, Events $events)
    {
        parent::__construct();
        $this->calendarList = $calList;
        $this->alarms = $alarms;
        $this->events = $events;
    }

    /**
     * get calendars from user from google
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function getCalendars()
    {
        // Get the API client and construct the service object.
        $client = $this->getClient(env('GOOGLE_CALENDARS'));
        //if client is array => it has redirect Url
        //if client is not array -=> client object
        if (is_array($client)) {

            return redirect($client['redirect']);
        }
        $service = new Google_Service_Calendar($client);

        // Get list of followed callendars
        $calendarList = $service->calendarList->listCalendarList();
        $calendars = $this->listCalendars($calendarList);
//    dd($calendars);
       $data = [
           'calendarList' => $calendars,
       ];
//        dd($data);

       return view('setup.calendar',$data);
    }

    /**
     * get all events from calendar user selected
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function getEvents()
    {
        $client = $this->getClient(env('GOOGLE_EVENTS'));
        if (is_array($client)){

            return redirect($client['redirect']);
        }

        $service = new Google_Service_Calendar($client);
        $calList = Auth::user()->getCalendars; //Calendar ID's ophalen
        $events = $this->listEvents($calList,$service);

        //sort by start date
        $start = array();
        foreach ($events as $key => $row){
            $start[$key] = $row['start'];
        }

        array_multisort($start, SORT_ASC, $events);
        $data = ['events' => $events];

        return view('setup.events',$data);
    }

    /**
     * set the calendars the user want to follow
     *
     * @param SetCalendarRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setCalendars(SetCalendarRequest $request)
    {
        $data = $request->all();
        foreach ($data['calendar'] as $key => $calendar) {
            $update = $this->calendarList->GetCalendar($calendar);
            $update->save();
            if ($data['action']=='follow!'){
                $update->follow = 1;
            }elseif ($data['action']=='unfollow!'){
                $update->follow = 0;
            }
            $update->save();
        }

        return redirect()->route('calendars');
    }

    /**
     * select the events the user want an alarm for
     *
     * @param SetEventRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setEvents(SetEventRequest $request)
    {
        $data = $request->all();
        $this->setAlarms($data);

        return redirect()->route('alarms');
    }

    /**
     * get acces from google
     *
     * @param $uri
     * @return array|Google_Client
     */
    public function getClient($uri)
    {
        $client = $this->setclient($uri);
        $accessToken = $this->checkAccessToken($client);
        if (!$accessToken){
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

        return $client;
    }







    /////////////HELPERS///////////////

    /**
     * setting the alarm
     *
     * @param $data
     */

    public function setAlarms($data)
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

    /**
     * takes the data needed for the calendar list
     *
     * @param $calendarList
     * @return array
     */
    public function listCalendars($calendarList)
    {
        $calendars =array();
//        $calendars['following'] = false;
        while(true) {
            foreach ($calendarList->getItems() as $calendarListEntry) {
                // check if exist & followed or not
                $find = $this->calendarList->GetCalendar($calendarListEntry->id);
                $follow = false;
                if($find->count()){
                    if($find->follow !=0) {
                        $follow = true;
//                        $calendars['following'] = true;
//                        dd('in');
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
                    'follow' => $follow,
                ];
            }

            return $calendars;
        }
    }

    /**
     * lists all the events up by order of date and time
     *
     * @param $calList
     * @param $service
     * @return array
     */
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
                if (!$find->count()) {
                    $start = new Carbon( $item['modelData']['start']['dateTime']);
                    $end = new Carbon( $item['modelData']['end']['dateTime']);
                    $summary = $item['summary'];
                    $pieces = explode(' ',$start);
                    $startDate = $pieces[0];
                    $startTime = $pieces[1];
                    $data = $item->id . '/' . $value->calendar_id . '/' . $start . '/' . $end.'/'. $summary;
                    $event = [
                        'summary'=> $summary,
                        'start'=> $start, //->format('Y-m-d\TH:i')
                        'end' => $end,
                        'startDate' => $startDate,
                        'startTime' => $startTime,
                        'event_id' => $item->id,
                        'data' => $data,
                    ];

                    $eventToDb = [
                        'user_id' => $this->user_id,
                        'summary'=> $summary,
                        'start'=> $start, //->format('Y-m-d\TH:i')
                        'end' => $end,
                        'event_id' => $item->id,
                    ];
                    $this->events->firstOrCreate($eventToDb);

                    $events[]=$event;
                }
            }
        }
        
        return $events;
    }


    /**
     * set up info to connect to google
     *
     * @param $uri
     * @return Google_Client
     */
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

    /**
     * check if acces is still valid
     *
     * @param $client
     * @return null
     */
    public function checkAccessToken($client)
    {
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
        if ($client->isAccessTokenExpired()){
            $client->refreshToken(Auth::user()->refreshtoken);
//            $user = Auth::user();
//            $user->refreshtoken = $client->refreshToken($user->refreshtoken);
//            $user->save();
            setcookie('accessToken', $client->getAccessToken(), time() + (86400 * 30), "/"); // 86400 = 1 day
        }

        return $client;
    }


}
