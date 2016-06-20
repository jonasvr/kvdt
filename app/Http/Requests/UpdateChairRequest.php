<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Devices;
use App\Chairs;

class UpdateChairRequest extends Request
{
    /**
     * @var Devices
     */
    protected $devices;
    /**
     * @var Chairs
     */
    protected $chairs;

    public function __construct(Devices $devices, Chairs $chairs)
    {
        $this->devices = $devices;
        $this->chairs = $chairs;
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $device = $this->devices->where('device_id','=',$this->device_id)->first();
        if ($device->count()){
            if ($this->chairs->where('device_id','=',$device->id)->first()->count()){
                return true;
            }
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'state' => 'required',
        ];
    }
}
