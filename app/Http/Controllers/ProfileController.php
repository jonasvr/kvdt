<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Devices;
use App\Koten;
use Auth;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\AddDeviceRequest;
use App\Showers;


class ProfileController extends Controller
{
    /**
     * @var Devices
     * @var Koten
     * @var Showers
     */
    protected $devices;
    protected $koten;
    protected $showers;

    /**
     * ProfileController constructor.
     * @param Devices $devices
     */
    public function __construct(Devices $devices, Koten $koten, Showers $showers)
    {
        parent::__construct();
        $this->devices = $devices;
        $this->koten = $koten;
        $this->showers = $showers;
    }

    /**
     *all the users data and devices info
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile()
    {
        $data=[
            'devices' => Auth::user()->getDevices(),
        ];
        return view('profile.profile',$data);
    }

    /**
     * updates the users personal data
     *
     * @param UpdateProfileRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateProfileRequest $request)
    {
        $data = $request->all();
        $user = Auth::user();
        $user->mailAlias = $data['mailAlias'];
        $user->save();
        return back();
    }

    /**
     * adds a new device and checks the type
     *
     * @param AddDeviceRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addDevice(AddDeviceRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = $this->user_id;
        $type = explode('@',$data['device_id']);

        switch ($type[0]){
            case 'w':
                $data['device_type'] = 'wekker';
                break;
            case 's':
                $data['device_type'] = 'shower';
                $showerInput =[
                    'koten_id' => Auth::user()->koten_id,
                ];
                break;
        }
        $device = $this->devices->create($data);
        if(isset($showerInput)){
            $showerInput['device_id'] = $device->id;
            dd($showerInput);
            $this->showers->create($showerInput);
        }
        return back();
    }

    /**
     * adds living place
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addKot(Request $request)
    {
        $data = $request->all();
       if(!$request->pass==''){
            $data['user_id'] = $this->user_id;
            $kot = $this->koten->create($data);
       }else{
            $kot = $this->koten->FindId($data['kot_id']);
       }
        $message=[
        'error'=>"kot isn't activated",
            ];
        if($kot->count()){
            Auth::user()->koten_id = $kot->id;
            Auth::user()->save();
            $message=[
                'success'=>'kot updated',
            ];
        }



        return back()->withErrors($message);
    }
}
