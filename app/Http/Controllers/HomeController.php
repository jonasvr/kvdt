<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Alarms;
use App\Devices;
use App\Showers;
use App\Events;
use Carbon\Carbon;
use Auth;
use JavaScript;


class HomeController extends Controller
{
    /**
     * @var Alarms
     */
    protected $alarms;
    /**
     * @var Showers
     */
    protected $showers;
    /**
     * @var Devices
     */
    protected $devices;
    /**
     * @var Events
     */
    protected $events;


    /**
     * HomeController constructor.
     * @param Alarms $alarms
     * @param Devices $devices
     * @param Showers $showers
     * @param Events $events
     */
    public function __construct(
        Alarms $alarms,
        Devices $devices,
        Showers $showers,
        Events $events
    ){
        $this->middleware('auth'); //=>route
        parent::__construct();
        $this->alarms = $alarms;
        $this->devices = $devices;
        $this->showers = $showers;
        $this->events = $events;
    }

    /**
     * setting home page, with all device data
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $alarms = $this->getAlarms();
        $showers = $this->showers->ShowerByKot(Auth::user()->koten_id)->get();
        $agenda = $this->events->GetAgenda(Auth::user()->id)->all();

        foreach ($agenda as $key => $event){
//            dd($agenda[$key]->start);
            $start = new Carbon($agenda[$key]->start);
            $agenda[$key]->start = $start->format('Y-m-j H:i:s');

            $end = new Carbon($agenda[$key]->end);
            $agenda[$key]->end = $end->format('Y-m-j h:i:s');
        }

        $data = [
            'alarms' => $alarms,
            'showers' => $showers,
            'agenda' => $agenda,
            'today' => Carbon::today()->format('j-m-y'),
        ];

        JavaScript::put([
            'showers' => $showers,
            'koten_id' => Auth::user()->koten_id,
        ]);
        return view('home', $data);
    }

    private function getAlarms(){
        $alarms = $this->alarms
            ->CheckUser($this->user_id)
            ->Today()
            ->orderBy('alarmDate','ASC')
            ->orderBy('alarmTime','ASC')
            ->get();

        return $alarms;
    }

    private function getShowers(){
        $showers = $this->showers->ShowerByKot(Auth::user()->koten_id)->get();

        return $showers;
    }
}
