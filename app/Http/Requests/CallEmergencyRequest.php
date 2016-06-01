<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CallEmergencyRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
//        $device =   $this->devices->CheckID($data['device_id']);
//        $alarm  =   $this->alarms->CheckID($data['alarm_id']);
//        if($device->user_id == $alarm->user_id)  
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
