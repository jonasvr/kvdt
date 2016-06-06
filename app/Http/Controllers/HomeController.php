<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Alarms;


class HomeController extends Controller
{
    protected $alarms;
    public function __construct(Alarms $alarms)
    {
        $this->middleware('auth');
        $this->alarms = $alarms;
        parent::__construct();
    }

    /**
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
//        dd($alarms);
        $data = ['alarms' => $alarms];
        return view('home', $data);
    }
}
