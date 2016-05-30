<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Mail;

class EmailController extends Controller
{
    public function send(Request $request){
        $title = $request->input('title');
       $content = $request->input('content');
    //    dd($content);

       Mail::send('mails.send', ['title' => $title, 'content' => $content], function ($message)
       {

           $message->from('jonas.vanreeth@student.kdg.be', 'Christian Nwamba');

           $message->to('js0nvr@gmail.com');

       });

       return response()->json(['message' => 'Request completed']);
   }
}
