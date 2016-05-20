<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Alarms;

class AlarmController extends Controller
{
    public function getAlarms(){
        $alarms = Alarms::where('user_id','=',Auth::user()->id)->get();
        $data = ['alarms' => $alarms];

        return View('alarms.alarms', $data);
    }

    public function setAlarms(Request $request){
        $data   =   $request->all();
        dd($data);
    }
}
