<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Devices;
use App\Koten;
use Auth;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\AddDeviceRequest;
use App\Showers;
use App\ApplyKotens;


class ProfileController extends Controller
{
    /**
     * @var Devices
     * @var Koten
     * @var Showers
     * @var ApplyKotens
     * @var User
     */
    protected $devices;
    protected $koten;
    protected $showers;
    protected $apply;
    protected $user;

    /**
     * ProfileController constructor.
     * @param Devices $devices
     */
    public function __construct
    (
        Devices $devices,
        Koten $koten,
        Showers $showers,
        ApplyKotens $apply,
        User $user
    ){
        parent::__construct();
        $this->devices = $devices;
        $this->koten = $koten;
        $this->showers = $showers;
        $this->apply = $apply;
        $this->user = $user;
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
            'applies' => $this->getApplies(),
            'showers' => $this->showers->ShowerByKot(Auth::user()->koten_id)->get(),
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
            $showerInput=[
                'device_id' => $device->id,
            ];
//            dd($showerInput);
            $this->showers->create($showerInput);
        }
        return back();
    }

    ///////////////Helper///////////////////////

    private function getApplies()
    {
        $data=[];
        $isAdmin = $this->koten->IsAdmin(Auth::user()->id);
        if($isAdmin->count())
        {
            $applies = $this->apply->GetApplies($isAdmin->id);
            foreach($applies as $key => $apply){
                $user=Auth::user()->GetInfo($apply->user_id);
                $data[]=[
                    'user_id' => $apply->user_id,
                    'name' => $user->name,
                    'apply_id' => $apply->id,
                ];
            }
        }
        return $data;
    }
}
