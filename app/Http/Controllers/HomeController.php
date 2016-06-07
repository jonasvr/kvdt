<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Alarms;


class HomeController extends Controller
{
    /**
     * @var Alarms
     */
    protected $alarms;

    /**
     * HomeController constructor.
     * @param Alarms $alarms
     */
    public function __construct(Alarms $alarms)
    {
        $this->middleware('auth'); //=>route
        parent::__construct();
        $this->alarms = $alarms;
    }

    /**
     * setting home page, with all device data
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $alarms = $this->alarms
            ->CheckUser($this->user_id)
            ->Today()
            ->orderBy('alarmDate','ASC')
            ->orderBy('alarmTime','ASC')
            ->get();
        $data = ['alarms' => $alarms];
        return view('home', $data);
    }
}
