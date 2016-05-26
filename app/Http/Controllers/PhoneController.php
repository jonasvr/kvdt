<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use Illuminate\Support\MessageBag;
use App\PhoneNumbers;
use Session;
use Auth;

class PhoneController extends Controller
{
    public function get()
    {
        $data=[
                'nrs' => $this->Numbers(),
        ];
        return view('contacts.numbers',$data);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'nr' => 'phone:BE|unique:phone_numbers,nr',
            //https://github.com/Propaganistas/Laravel-Phone/blob/master/README.md
        ]);
        if ($validator->fails()) {

            $data=[
                    'nrs' => $this->Numbers(),
            ];
            return view('contacts.numbers',$data)
                        ->withErrors($validator);
        }
        $data = $request->all();
        $nr = new PhoneNumbers();
        $nr->user_id=   Auth::user()->id;
        $nr->name   =   $data['name'];
        $nr->nr     =   $data['nr'];
        if($nr->save())
        {
            Session::flash('success', 'nr toegevoegt');
        }
         return Redirect()->route('numbers');
    }

    public function delete($id){
        $nr = PhoneNumbers::find($id);
        // dd($nr);
        if($nr==null)
        {
            $data=[
                    'nr' => $this->Numbers(),
            ];
            return view('contacts.numbers',$data) //redirect met error?
                            ->withErrors(['message'=>'foutieve id']);
        }

        $nr->delete();
        // $mail->save();
        Session::flash('success', 'nr verwijderd');

        return Redirect()->route('numbers');
    }

    public function getEdit($id){
        $nr = PhoneNumbers::find($id);
        if(!$nr)
        {
            return Redirect()->route('numbers')
                            ->withErrors(['message'=>'foutieve id']);
        }
        $data=[
                'nrs' => $this->Numbers(),
                'edit'  => $nr,
        ];
        return view('contacts.numbers',$data);
    }

    public function edit(Request $request){
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'nr'    => 'phone:BE',
            'id'    =>'required',
        ]);
        if ($validator->fails()) {

            $data=[
                    'nr' => $this->Numbers(),
            ];
            return view('contacts.numbers',$data)
                        ->withErrors($validator);
        }

        $data=$request->all();
        $nr = PhoneNumbers::find($data['id']);
        $nr->nr     = $data['nr'];
        $nr->name   = $data['name'];
        $nr->save();

        return redirect()->route('numbers');
    }

    public function Numbers(){
        return $nrs = PhoneNumbers::where('user_id','=',Auth::user()->id)->get();
    }
}
