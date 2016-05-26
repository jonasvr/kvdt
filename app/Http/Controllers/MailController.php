<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Mails;
use Validator;
use Session;
use Auth;


class MailController extends Controller
{
    public function get()
    {
        $data=[
                'mails' => $this->Mails(),
                'action'=> 'add',
        ];
        return view('contacts.mails',$data);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'mail' => 'email|unique:mails,mail',
        ]);
        if ($validator->fails()) {

            $data=[
                    'mails' => $this->Mails(),
            ];
            return view('contacts.mails',$data)
                        ->withErrors($validator);
        }
        $data = $request->all();
        $mails = new Mails();
        $mails->user_id =   Auth::user()->id;
        $mails->name    =   $data['name'];
        $mails->mail    =   $data['mail'];
        if($mails->save())
        {
            Session::flash('success', 'mail toegevoegt');
        }
         return Redirect()->route('mails');
    }

    public function delete($id){
        $mail = Mails::find($id);
        // dd($mail);
        if($mail==null)
        {
            $data=[
                    'mails' => $this->Mails(),
            ];
            return view('contacts.mails',$data) //redirect met error?
                            ->withErrors(['message'=>'foutieve id']);
        }

        $mail->delete();
        // $mail->save();
        Session::flash('success', 'mail verwijderd');

        return Redirect()->route('mails');
    }

    public function getEdit($id){
        $mail = Mails::find($id);
        if(!$mail)
        {
            return Redirect()->route('mails')
                            ->withErrors(['message'=>'foutieve id']);
        }
        $data=[
                'mails' => $this->Mails(),
                'edit'  => $mail,
        ];
        return view('contacts.mails',$data);
    }

    public function edit(Request $request){
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'mail' => 'email',
            'id'    =>'required',
        ]);
        if ($validator->fails()) {

            $data=[
                    'mails' => $this->Mails(),
            ];
            return view('contacts.mails',$data)
                        ->withErrors($validator);
        }

        $data=$request->all();
        $mail = Mails::find($data['id']);
        $mail->mail = $data['mail'];
        $mail->name = $data['name'];
        $mail->save();

        return redirect()->route('mails');
    }



    public function Mails(){
        return $mails = Mails::where('user_id','=',Auth::user()->id)->get();
    }
}
