<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Devices;
use App\Alarms;

class CallEmergencyRequest extends Request
{
    /**
     * @var Devices
     * @var Alarms
     */
    protected $devices;
    protected $alarms;

    /**
     * CallEmergencyRequest constructor.
     * @param Devices $devices
     * @param Alarms $alarms
     */
    public function __construct(Devices $devices, Alarms $alarms)
    {
        $this->devices = $devices;
        $this->alarms = $alarms;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $device =  $this->devices->CheckID($this->device_id);
        $alarm = $this->alarms->CheckID($this->alarm_id);
        if($device->user_id == $alarm->user_id) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'device_id'     => 'required',
            'alarm_id'      => 'required|numeric',
        ];
    }
}
