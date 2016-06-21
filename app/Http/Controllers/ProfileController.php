<?php

namespace App\Http\Controllers;

use App\Chairs;
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
use JavaScript;


class ProfileController extends Controller
{
    /**
     * @var Devices
     */
    protected $devices;
    /**
     * @var Koten
     */
    protected $koten;
    /**
     * @var Showers
     */
    protected $showers;
    /**
     * @var ApplyKotens
     */
    protected $apply;
    /**
     * @var User
     */
    protected $user;
    /**
     * @var Chairs
     */
    protected $chair;

    /**
     * ProfileController constructor.
     * @param Devices $devices
     * @param Koten $koten
     * @param Showers $showers
     * @param ApplyKotens $apply
     * @param User $user
     * @param Chairs $chair
     */
    public function __construct
    (
        Devices $devices,
        Koten $koten,
        Showers $showers,
        ApplyKotens $apply,
        User $user,
        Chairs $chair
    ){
        parent::__construct();
        $this->devices = $devices;
        $this->koten = $koten;
        $this->showers = $showers;
        $this->apply = $apply;
        $this->user = $user;
        $this->chair = $chair;
    }

    /**
     *all the users data and devices info
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile()
    {
        $data=[
            'devices' => Auth::user()->getDevices()->count(),
            'applies' => $this->getApplies(),
            'showers' => $this->showers->ShowerByKot(Auth::user()->koten_id)->get(),
        ];

        JavaScript::put([
            'koten_id' =>  Auth::user()->koten_id,
            'showers' => $this->showers->ShowerByKot(Auth::user()->koten_id)->get(),
            'devices' => Auth::user()->getDevices(),
        ]);
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
                $device = $this->devices->create($data);
                break;
            case 's':
                $data['device_type'] = 'shower';
                $device = $this->devices->create($data);
                $input=['device_id' => $device->id];
                $this->newDeviceToKot($this->showers->create($input));
                break;
            case 'c':
                $data['device_type'] = 'chair';
                $device = $this->devices->create($data);
                $input=['device_id' => $device->id];
                $this->chair->create($input);
                break;
        }
        return back();
    }

    ///////////////Helper///////////////////////

    private function newDeviceToKot($new)
    {
        $new->koten_id = Auth::user()->koten_id;
        $new->save();
    }

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
