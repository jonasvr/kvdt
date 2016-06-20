<?php

namespace App\Http\Controllers;

use App\Events\ShowerUpdate;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Alarms;
use App\Devices;
use App\Mails;
use App\Emergencies;
use App\Messages;
use App\PhoneNumbers;
use App\Showers;
use App\Jobs\SendMailJob;
use App\Jobs\SendTextJob;
use App\Jobs\ConfirmMail;
use App\Http\Requests\SetAlarmRequest;
use App\Http\Requests\CallEmergencyRequest;
use App\Http\Requests\UpdateshowerRequest;
use App\Http\Requests\UpdateChairRequest;


class ApiController extends Controller
{
    /**
     * @var Devices
     */
    protected $devices;
    /**
     * @var Alarms
     */
    protected $alarms;
    /**
     * @var Emergencies
     */
    protected $emergencies;
    /**
     * @var Messages
     */
    protected $messages;
    /**
     * @var Mails
     */
    protected $mails;
    /**
     * @var PhoneNumbers
     */
    protected $nrs;
    /**
     * @var Showers
     */
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
        $alarm = $this->alarms->CheckID($request->alarm_id);
        $emergency = $this->emergencies->FirstIfExist($alarm->id);
        $content = $this->messages->where('id','=',$emergency->message_id)->first();
        $user = User::GetInfo($alarm->user_id);
        switch ($emergency->contact_type) {
            case 0:
                $this->sendMail($emergency,$content,$user);
                break;
            case 1:
                $this->sendText($emergency,$content,$user);
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
        
        
        event(new ShowerUpdate($this->showers));

        return 'succes';
    }

    public function ShowerGet($device_id, $state)
    {
        $device = $this->devices->where('device_id','=',$device_id)->firstOrFail();
        $shower = $this->showers->where('device_id','=',$device->id)->firstOrFail();
        $shower->state = $state;
        $shower->save();


        event(new ShowerUpdate($this->showers));

        return 'succes';
    }


    public function Chair(UpdateChairRequest $request)
    {

    }


/////////////////Helpers//////////////////////
    private function sendMail($emergency, $content,$user)
    {
        $to = $this->mails->find($emergency->contact_id);
//        $this->alarms;
        if ($user->mailAlias) {
            $from = $user->mailAlias;
        }else{
            $from = $user->email;
        }
        $this->dispatch(new SendMailJob(
            $content->title,
            $content->message,
            $to->mail, $from,
            $user
        ));


        $this->dispatch(new ConfirmMail(
            $content->message,
            $to->mail,
            $user
        ));
    }

    private function sendText($emergency, $content,$user)
    {
        echo 'sending text 1';
        $to = $this->nrs->findOrFail($emergency->contact_id);
        $this->dispatch(new SendTextJob($content->message, $to->nr));

        $this->dispatch(new ConfirmMail(
            $content->message,
            $to->mail,
            $user->name
        ));
    }
}
