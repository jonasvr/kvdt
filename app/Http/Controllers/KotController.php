<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Koten;
use Auth;
use App\User;
use App\Http\Requests\AddKotRequest;
use App\ApplyKotens;


class KotController extends Controller
{
    /**
     * @var Koten
     * @var ApplyKotens
     * @var User
     */
    protected $koten;
    protected $apply;
    protected $user;

    /**
     * KotController constructor.
     * @param Koten $koten
     * @param ApplyKotens $apply
     * @param User $user
     */
    public function __construct
    (
        Koten $koten,
        ApplyKotens $apply,
        User $user
    ){
        parent::__construct();
        $this->koten = $koten;
        $this->apply = $apply;
        $this->user = $user;
    }
    /**
     * adds living place
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addKot(AddKotRequest $request)
    {
        $data = $request->all();
        $kot = $this->koten->FindId($data['kot_id']);
        if($request->pass==$kot->pass && $kot->user_id !==''){
            //admin_id meegeven, huidige user
            $kot->update(['user_id' => $this->user_id]);
            $user = Auth::user();
            $user->koten_id = $kot->id;
            $user->save();
        }
        //geen admin geset of admin niet gelijk aan huidige user
        if($kot->user_id !== 0 && $kot->user_id !== Auth::user()->id){
            $add=[
                'kot_id' => $kot->id,
                'user_id' => Auth::user()->id,
            ];
            $this->apply->create($add);
        }

        return back();
    }


    /**
     * @param $applyUser_id
     * @param $apply_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function acceptApply($applyUser_id, $apply_id)
    {
        $applyUser = $this->user->GetInfo($applyUser_id);
        $applyUser->koten_id = Auth::user()->koten_id;
        $applyUser->save();
        $this->removeApply($apply_id);
        return back();
    }

    /**
     * @param $apply_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeApply($apply_id)
    {
        $this->apply->find($apply_id)->delete();
        return back();
    }
    
}
