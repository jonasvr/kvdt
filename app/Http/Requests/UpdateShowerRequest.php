<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Devices;
use App\Showers;

class UpdateShowerRequest extends Request
{
    /**
     * @var Devices
     */
    protected $devices;
    /**
     * @var Showers
     */
    protected $showers;

    /**
     * UpdateShowerRequest constructor.
     * @param Devices $devices
     * @param Showers $showers
     */
    public function __construct(Devices $devices, Showers $showers)
    {
        $this->devices = $devices;
        $this->showers = $showers;
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
            if ($this->showers->where('device_id','=',$device->id)->first()->count()){
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
            'state' => 'required|boolean',
        ];
    }
}
