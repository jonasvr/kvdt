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

    //////////////////VIEW////////////////////////

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAlarms() //view
    {
        $alarms = $this->alarms
            ->CheckUser($this->user_id)
            ->Today()
            ->get();
        $data = ['alarms' => $alarms];

        return view('alarms.alarms', $data);
    }

    /**
     * @param $alarm_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function emergency($alarm_id) //view
    {
        $numbers = $this->nrs->getAll($this->user_id);
        $mails = $this->mail->getAll($this->user_id);
        $messages = $this->messages->getAll($this->user_id);
        $emergency = $this->emergencies->FirstIfExist($alarm_id);

        $data =[
            'numbers' =>  $numbers,
            'mails' => $mails,
            'messages' => $messages,
            'alarm_id' => $alarm_id,
            'emergency' => $emergency,
        ];

        return view('alarms.emergency',$data);
    }
    //////////////////CRUD////////////////////////

    /**
     * @param UpdateAlarmRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function updateAlarms(UpdateAlarmRequest $request) //crud
    {
        $data = collect($request->only('event','action','alarmTime'));
        $events = $data['event'];

        if (!$events) {
            return back()->withErrors(['select something']);
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
                        $this->delete($event);
                        break;
                    case 'update!':
                        $alarm->update($time[$key]);
                        break;
                }
            }
        }

        return redirect()->route('alarms');
    }



    /**
     * @param UpdateEmergRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateEmergency(UpdateEmergRequest $request) //crud
    {
        $data = $request->all();
            $emergency = $this->emergencies->FirstIfExist($data['alarm_id']);
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

    //////////////////HELPERS////////////////////////

    /**
     * @param $alarm_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($alarm_id) //helper
    {
            $emerg = $this->emergencies->FirstIfExist($alarm_id);
            if($emerg){
                $emerg->delete();
            }

        return back();
    }
}
