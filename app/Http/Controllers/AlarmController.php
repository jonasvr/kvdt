<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Auth;
use App\Alarms;
use App\PhoneNumbers;
use App\Mails;
use App\Messages;
use App\Emergencies;
use App\Http\Requests\UpdateAlarmRequest;
use App\Http\Requests\UpdateEmergRequest;

class AlarmController extends Controller
{
    protected $alarms;
    protected $nrs;
    protected $mail;
    protected $messages;
    protected $emergencies;

    public function __construct(
        Alarms $alarms,
        PhoneNumbers $nrs, Mails $mail,
        Messages $messages,
        Emergencies $emergencies
    )
    {
        $this->alarms = $alarms;
        $this->nrs = $nrs;
        $this->mail = $mail;
        $this->messages = $messages;
        $this->emergencies = $emergencies;
        parent::__construct();
    }

    public function getAlarms()
    {
        $alarms = $this->alarms
            ->CheckUser($this->user_id)
            ->Today()
            ->get();
        $data = ['alarms' => $alarms];

        return view('alarms.alarms', $data);
    }

    public function updateAlarms(UpdateAlarmRequest $request)
    {
        $data = collect($request->only('event','action','alarmTime'));
        $events = $data['event'];

        if (!$events) {
            return back()->withErrors(['message'=>'select something']);
        }
        else{

            $action = $data['action'];
            $time = $data['alarmTime'];

            foreach ($events as $key => $event) {

                $alarm = $this->alarms
                    ->CheckUser($this->user_id)
                    ->CheckEvent($event)
                    ->first();

                switch ($action) {
                    case 'remove!':
                        $alarm->delete();
                        break;
                    case 'update!':
                        $alarm->update($time[$key]);
                        break;
                }
            }
        }

        return route('alarms');
    }

    public function emergency($alarm_id)
    {
        $numbers = $this->nrs->getAll($this->user_id);
        $mails = $this->mail->getAll($this->user_id);
        $messages = $this->messages->getAll($this->user_id);
        $emergency = $this->emergencies->exist($alarm_id);

        $data =[
            'numbers' =>  $numbers,
            'mails' => $mails,
            'messages' => $messages,
            'alarm_id' => $alarm_id,
            'emergency' => $emergency,
        ];

        return view('alarms.emergency',$data);
    }

    public function updateEmergency(UpdateEmergRequest $request)
    {
        $data = $request->all();
        $emergency = $this->emergencies->exist($data['alarm_id']);
        (!$emergency)?$emergency = new Emergencies():'';
        $emergency->contact_id = $data['contact_id'];
        $emergency->message_id = $data['message_id'];
        $emergency->alarm_id   = $data['alarm_id'];
        if($data['type']=='mail') {
            $emergency->contact_type = 0;
        }else if($data['type']=='sms') {
            $emergency->contact_type = 1;
        }
        $emergency->save();

        return redirect()->route('alarms');
    }

    public function delete($alarm_id)
    {
            $this->alarms
                ->CheckUser($this->user_id)
                ->CheckId($alarm_id)
                ->delete();
            $emerg = $this->emergencies
                ->exist($alarm_id);
            if($emerg){
                $emerg->delete();
            }

        return back();
    }
}
