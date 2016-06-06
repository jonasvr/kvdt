<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Alarms;
use App\Devices;
use App\Mails;
use App\Emergencies;
use App\Messages;
use App\PhoneNumbers;
use App\Jobs\SendMailJob;
use App\Jobs\SendTextJob;
use App\Http\Requests\SetAlarmRequest;
use App\Http\Requests\CallEmergencyRequest;




class ApiController extends Controller
{
    protected $devices;
    protected $alarms;
    protected $emergencies;
    protected $messages;
    protected $mails;
    protected $nrs;

    /**
     * ApiController constructor.
     * @param Devices $devices
     * @param Alarms $alarms
     * @param Emergencies $emergencies
     * @param Messages $messages
     * @param Mails $mails
     * @param PhoneNumbers $nrs
     */
    public function __construct(
        Devices $devices,
        Alarms $alarms,
        Emergencies $emergencies,
        Messages $messages,
        Mails $mails,
        PhoneNumbers $nrs
    )
    {
        $this->devices = $devices;
        $this->alarms = $alarms;
        $this->emergencies = $emergencies;
        $this->messages = $messages;
        $this->mails = $mails;
        $this->nrs = $nrs;
    }

    //////////////////Calls////////////////////////

    /**
     * apicall to get earliest alarm from now
     *
     * @param SetAlarmRequest $request
     * @return int|string
     */
    public function setAlarm(SetAlarmRequest $request){
        $data = $request->all();
        $device = $this->devices->CheckID($data['device_id']);
//        dd($device);
        if ($device) {
            $alarm = $this->alarms->NextAlarm($device->user_id);
        }
        $awnser = 0;
        if ($alarm->count()){
            $awnser = $alarm->alarmTime . ":" . $alarm->id;
        }
        return $awnser;
    }

    /**
     * api call when 3x snoozed -> send emergency
     *
     * @param CallEmergencyRequest $request
     * @return mixed
     */
    public function emergency(CallEmergencyRequest $request){

        $data = $request->all();
        $device = $this->devices->CheckID($data['device_id']);
        $alarm = $this->alarms->CheckID($data['alarm_id']);
        if($device->user_id == $alarm->user_id)
        {
            $emergency = $this->emergencies->FirstIfExist($alarm->id);
            $content = $this->messages->find($emergency->message_id);
            if(!$emergency->contact_type) // 0 => mail
            {
                $to = $this->mails->find($emergency->contact_id);
                $from = Auth::user()->email;
                if(Auth::user()->mailAlias)
                {
                    $from = Auth::user()->mailAlias;
                }
                return $this->dispatch(new SendMailJob(
                    $content->title,
                    $content->message,
                    $to->mail,$from,
                    Auth::user()->name
                ));
            }else {
                echo 'sending text 1';
                $to = $this->nrs->find($emergency->contact_id);

                return $this->dispatch(new SendTextJob($content->message, $to->nr));
            }
        }
    }
}
