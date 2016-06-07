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
    /**
     * @var Devices
     */
    protected $devices;

    /**
     * ProfileController constructor.
     * @param Devices $devices
     */
    public function __construct(Devices $devices)
    {
        parent::__construct();
        $this->devices = $devices;
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
                break;
        }
        $this->devices->create($data);
        
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
        $this->devices->create($data);

        return back();
    }
}
