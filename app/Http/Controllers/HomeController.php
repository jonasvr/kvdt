<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Alarms;
use App\Devices;
use App\Showers;
use Auth;


class HomeController extends Controller
{
    /**
     * @var Alarms
     * @var Devices
     * @var Showers
     */
    protected $alarms;
    protected $showers;
    protected $devices;

    /**
     * HomeController constructor.
     * @param Alarms $alarms
     */
    public function __construct(Alarms $alarms, Devices $devices, Showers $showers )
    {
        $this->middleware('auth'); //=>route
        parent::__construct();
        $this->alarms = $alarms;
        $this->devices = $devices;
        $this->showers = $showers;
    }

    /**
     * setting home page, with all device data
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $alarms = $this->getAlarms();
        $showers = $this->getShowers();

        $data = [
            'alarms' => $alarms,
            'showers' => $showers,
        ];
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
        if ($showers->count())
        {
            return $showers;
        }else{
            return 0;
        }
    }
}
