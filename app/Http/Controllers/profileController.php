<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Devices;
use Auth;

class ProfileController extends Controller
{
    public function addDevice(Request $request){
        $data = $request->all();
        // dd($data);
        $input = new Devices();
        $input->user_id = Auth::user()->id;
        $input->device_id = $data['device_id'];
        $input->save();

        return Redirect()->route('devices');
    }
}
