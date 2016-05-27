<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Alarms;
use Carbon\Carbon;
use App\Devices;
use Validator;

class ApiController extends Controller
{
    public function setAlarm(Request $request){
        $validator = Validator::make($request->all(), [
            'device_id' => 'required',
        ]);
        $data = $request->all();
        $device = Devices::checkID($data['device_id']);
        if ($device) {
            $alarm = Alarms::nextAlarm($device->user_id)
        }
        return $alarm->alarmTime;
    }

    public function emergency(Request $request){
        $validator = Validator::make($request->all(), [
            'device_id'     => 'required',
            'alarm_id'      => 'required|numeric',
        ]);

        $data = $request->all();
        $device =   Devices::checkID($data['device_id']);
        $alarm  =   Alarms::checkID($data['alarm_id']);
        if($device->user_id == $alarm->user_id)
        {
            //do emergency
        }
    }
