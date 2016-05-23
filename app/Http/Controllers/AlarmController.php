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
        foreach ($data['event'] as $key => $event) {

            $alarm = Alarms::where('user_id', '=', Auth::user()->id)
                            ->where('event_id','=',$event)
                            ->first();

            switch ($data['action']) {
                case 'remove!':
                    $alarm->delete();
                    break;
                case 'update!':
                    $this->updateAlarm($alarm, $data['alarmTime'][$key], $data['alarmDate'][$key]);
                    break;
            }
        }

        return Redirect()->route('alarms');
    }


    public function updateAlarm($alarm,$time,$date){

        $alarm->alarmTime = $time;
        $alarm->save();
    }
}
