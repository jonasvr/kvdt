<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Devices;
use App\Alarms;

class CallEmergencyRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $device = Devices::CheckID($this->device_id);
        $alarm = Alarms::CheckID($this->alarm_id);
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
