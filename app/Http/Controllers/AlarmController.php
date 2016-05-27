<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Alarms;
use Carbon\Carbon;
use App\Devices;
use Validator;
use App\PhoneNumbers;
use App\Mails;
use App\Messages;
use App\Emergencies;

class AlarmController extends Controller
{
    public function getAlarms(){
        $alarms = Alarms::where('user_id','=',Auth::user()->id)
                        ->where('alarmDate', '>=' , carbon::today())
                        ->get();
        $data = ['alarms' => $alarms];

        return View('alarms.alarms', $data);
    }

    public function updateAlarms(Request $request){
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
                        $this->update($alarm, $data['alarmTime'][$key]);
                        break;
                }
            }
        }
        else{
            echo 'no change select something';
        }
        return Redirect()->route('alarms');
    }

    public function update($alarm,$time){
        $alarm->alarmTime = $time;
        $alarm->save();
    }


    public function emergency($alarm_id){
        $user_id    = Auth::user()->id;
        $numbers    = PhoneNumbers::getAll($user_id);
        $mails      = Mails::getAll($user_id);
        $messages   = Messages::getAll($user_id);
        $emergency  = Emergencies::exist($alarm_id);

        $data =[
            'numbers'   =>  $numbers,
            'mails'     =>  $mails,
            'messages'  =>  $messages,
            'alarm_id'  =>  $alarm_id,
            'emergency' =>  $emergency,
        ];
        return view('alarms.emergency',$data);
    }

    public function updateEmergency(Request $request){
        $data = $request->all();
        $emergency = Emergencies::exist($data['alarm_id']);
        // if(!$emergency)
        // {
        //     $emergency  = new Emergencies();
        // }
        $emergency->contact_id = $data['contact_id'];
        $emergency->message_id = $data['message_id'];
        $emergency->alarm_id   = $data['alarm_id'];
        if($data['type']=='mail')
        {
            $emergency->MailOrSms = 0;
        }else if($data['type']=='sms') {
            $emergency->MailOrSms = 1;
        }
        $emergency->save();

        return redirect()->route('alarms');
    }
}
