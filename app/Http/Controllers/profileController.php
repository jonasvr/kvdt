<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Devices;
use Auth;

class ProfileController extends Controller
{
    protected $devices;

    public function __construct(Devices $devices)
    {
        $this->devices = $devices;
        parent::__construct();
    }

    public function addDevice(Request $request){
        $data = $request->all();
        $data['user_id'] = $this->user_id;
        $this->devices->create($data);

        return redirect()->route('devices');
    }
}
