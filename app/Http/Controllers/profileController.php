<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Devices;
use Auth;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\AddDeviceRequest;


class ProfileController extends Controller
{
    protected $devices;

    /**
     * ProfileController constructor.
     * @param Devices $devices
     */
    public function __construct(Devices $devices)
    {
        $this->devices = $devices;
        parent::__construct();
    }

    public function profile()
    {
        $data=[
            'devices' => Auth::user()->getDevices(),
        ];
        return view('profile.profile',$data);
    }

    public function update(UpdateProfileRequest $request)
    {
        $data = $request->all();
        $user = Auth::user();
        $user->mailAlias = $data['mailAlias'];
        $user->save();
        return back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addDevice(AddDeviceRequest $request){
        $data = $request->all();
        $data['user_id'] = $this->user_id;
        $type = explode('@',$data['device_id']);

        if($type[0]=='w'){
            $data['device_type'] = 'wekker';
        }else if ($type[0]='s'){
            $data['device_type'] = 'shower';
        }
        $this->devices->create($data);
        
        return back();
    }

    public function addKot(Request $request){
        $data = $request->all();
        $this->devices->create($data);

        return back();
    }
}
