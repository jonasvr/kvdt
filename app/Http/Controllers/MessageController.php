<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Messages;
use Validator;
use Session;
use Auth;
class MessageController extends Controller
{
    public function get()
    {
        $data=[
                'messages' => $this->Messages(),
                'action'=> 'add',
        ];
        // dd($data);
        return view('contacts.messages',$data);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'     => 'required',
            'message'   => 'required',
        ]);
        if ($validator->fails()) {

            $data=[
                    'messages' => $this->Messages(),
            ];
            return view('contacts.messages',$data)
                        ->withErrors($validator);
        }
        $data = $request->all();
        $messages = new Messages();
        $messages->user_id      =   Auth::user()->id;
        $messages->message      =   $data['message'];
        $messages->title        =   $data['title'];
        if($messages->save())
        {
            Session::flash('success', 'Message toegevoegt');
        }
         return Redirect()->route('mess');
    }

    public function delete($id){
        $message = Messages::find($id);
        // dd($message);
        if($message==null)
        {
            $data=[
                    'messages' => $this->Messages(),
            ];
            return view('contacts.messages',$data) //redirect met error?
                            ->withErrors(['message'=>'foutieve id']);
        }

        $message->delete();
        // $mail->save();
        Session::flash('success', 'mail verwijderd');

        return Redirect()->route('mess');
    }


    public function getEdit($id){
        $message = Messages::find($id);
        if(!$message)
        {
            return Redirect()->route('mess')
                            ->withErrors(['message'=>'foutieve id']);
        }
        $data=[
                'messages' => $this->Messages(),
                'edit'  => $message,
        ];
        return view('contacts.messages',$data);
    }

    public function edit(Request $request){
        $validator = Validator::make($request->all(), [
            'title'  => 'required',
            'message' => 'required',
            'id'    =>'required',
        ]);
        if ($validator->fails()) {

            $data=[
                    'mails' => $this->Messges(),
            ];
            return view('contacts.messages',$data)
                        ->withErrors($validator);
        }
        $data=$request->all();
        $messages = Messages::find($data['id']);
        $messages->message = $data['message'];
        $messages->title = $data['title'];
        $messages->save();

        return redirect()->route('mess');
    }

    public function Messages(){
         $messages = Messages::where('user_id','=',Auth::user()->id)->get();
        //  dd($messages);
         return $messages;
    }
}
