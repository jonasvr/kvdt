<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Alarms;
use Carbon\Carbon;

class AlarmController extends Controller
{
    public function getAlarms(){
        $alarms = Alarms::where('user_id','=',Auth::user()->id)
                        ->where('alarmDate', '>' , carbon::today())
                        ->get();
        $data = ['alarms' => $alarms];

        return View('alarms.alarms', $data);
    }

    public function setAlarms(Request $request){
        $data   =   $request->all();
        if (isset($data['event'])) {
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
        }
        else{
            echo 'no change select something';
        }
        return Redirect()->route('alarms');
    }


    public function updateAlarm($alarm,$time,$date){

        $alarm->alarmTime = $time;
        $alarm->save();
    }
}
