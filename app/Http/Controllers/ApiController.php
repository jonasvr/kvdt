<?php

namespace App\Http\Controllers;

use App\Events\ShowerUpdate;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Alarms;
use App\Devices;
use App\Mails;
use App\Emergencies;
use App\Messages;
use App\PhoneNumbers;
use App\Showers;
use App\Jobs\SendMailJob;
use App\Jobs\SendTextJob;
use App\Http\Requests\SetAlarmRequest;
use App\Http\Requests\CallEmergencyRequest;
use App\Http\Requests\UpdateshowerRequest;


class ApiController extends Controller
{
    /** => what does this do here????
     * ApiController constructor.
     * @var Devices
     * @var Alarms
     * @var Emergencies
     * @var Messages
     * @var Mails
     * @var PhoneNumbers
     * @var Showers
     */

    protected $devices;
    protected $alarms;
    protected $emergencies;
    protected $messages;
    protected $mails;
    protected $nrs;
    protected $showers;

    /**
     * ApiController constructor.
     * @param Devices $devices
     * @param Alarms $alarms
     * @param Emergencies $emergencies
     * @param Messages $messages
     * @param Mails $mails
     * @param PhoneNumbers $nrs
     */
    public function __construct
    (
        Devices $devices,
        Alarms $alarms,
        Emergencies $emergencies,
        Messages $messages,
        Mails $mails,
        PhoneNumbers $nrs,
        Showers $showers
    ){
        $this->devices = $devices;
        $this->alarms = $alarms;
        $this->emergencies = $emergencies;
        $this->messages = $messages;
        $this->mails = $mails;
        $this->nrs = $nrs;
        $this->showers = $showers;
    }

    //////////////////Calls////////////////////////

    /**
     * apicall to get earliest alarm from now
     *
     * @param SetAlarmRequest $request
     * @return int|string
     */
    public function setAlarm(SetAlarmRequest $request)
    {
        $data = $request->all();
        $device = $this->devices->CheckID($data['device_id']);
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
    public function emergency(CallEmergencyRequest $request)
    {
        $alarm = $this->alarms->scopeCheckID($this->alarm_id);
        $emergency = $this->emergencies->FirstIffExist($alarm->id);
        $content = $this->messages->firstOrFail($emergency->message_id);
        switch ($emergency->contact_type) {
            case 0:
                $this->sendMail($emergency,$content);
                break;
            case 1:
                $this->sendText($emergency,$content);
                break;
        }
        
        return 'sended';
    }

    public function Shower(UpdateShowerRequest $request)
    {
        $device = $this->devices->where('device_id','=',$request->device_id)->firstOrFail();
//        dd($request->all());
        $shower = $this->showers->where('device_id','=',$device->id)->firstOrFail();
        $shower->state = $request->state;
        $shower->save();
        
        
        event(new ShowerUpdate($this->showers->all()));

        return 'succes';
    }
/////////////////Helpers//////////////////////
    private function sendMail($emergency, $content)
    {
        $to = $this->mails->find($emergency->contact_id);
        $from = Auth::user()->email;
        if (Auth::user()->mailAlias) {
            $from = Auth::user()->mailAlias;
        }
        $this->dispatch(new SendMailJob(
            $content->title,
            $content->message,
            $to->mail, $from,
            Auth::user()->name
        ));
    }

    private function sendText($emergency, $content)
    {
        echo 'sending text 1';
        $to = $this->nrs->findOrFail($emergency->contact_id);
        $this->dispatch(new SendTextJob($content->message, $to->nr));
    }

}
