<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Alarms;
use Carbon\Carbon;
use App\Devices;
use Validator;
use App\Mails;
use Mail;
use App\Emergencies;
use App\Messages;



class ApiController extends Controller
{
    public function setAlarm(Request $request){
        $validator = Validator::make($request->all(), [
            'device_id' => 'required',
        ]);
        $data = $request->all();
        $device = Devices::checkID($data['device_id']);
        if ($device) {
            $alarm = Alarms::nextAlarm($device->user_id);
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
            $emergencie = Emergencies::where('alarm_id','=',$alarm->id)->first();
            // dd($emergencie);
            $content = Messages::find($emergencie->message_id);
            if(!$emergencie->MailOrSms) // 0 => mail
            {
                $to = Mails::find($emergencie->contact_id);
                return $this->send($content->title,$content->message,$to->mail);
            }
        }
    }

    public function send($subject, $content, $to){
    //     $subject = $request->input('subject');
    //    $content = $request->input('content');

       Mail::send('mails.send', ['title' => $subject, 'content' => $content], function ($message) use ($subject, $to)
       {
           //from => Auth::user()->mailAlias
           $message->from('jonas.vanreeth@student.kdg.be', 'Jonas Van Reeth');
           $message->subject($subject);
           $message->to($to);

       });

       return response()->json(['message' => 'Request completed']);
    }
}
