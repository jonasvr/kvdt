<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Messages;
use Session;
use App\Http\Requests\AddMessageRequest;
use App\Http\Requests\EditMessageRequest;

class MessageController extends Controller
{
    /**
     * @var Messages
     */
    protected $mess;

    /**
     * MessageController constructor.
     * @param Messages $mess
     */
    public function __construct(Messages $mess)
    {
        parent::__construct();
        $this->mess = $mess;
    }

    /**
     * get all messages
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get()
    {
        $data=[
            'messages' => $this->mess->GetAll($this->user_id),
            'action'=> 'add',
        ];

        return view('contacts.messages',$data);
    }

    /**
     * get message data to edit
     *
     * @param $id
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEdit($id)
    {
        $message = $this->mess->find($id);
        if(!$message) {
            return redirect()->route('mess')
                ->withErrors(['message'=>'foutieve id']);
        }
        $data=[
            'messages' => $this->mess->GetAll($this->user_id),
            'edit' => $message,
        ];

        return view('contacts.messages',$data);
    }

    /**
     * delete message
     *
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $message = $this->mess->find($id);
        if($message==null || $message->user_id != $this->user_id){ //or had to be in middleware als for mails en numbers
            $data=[
                'messages' => $this->mess->GetAll($this->user_id),
            ];

            return view('contacts.messages',$data) //redirect met error?
                ->withErrors(['message'=>'foutieve id']);
        }

        $message->delete();
        Session::flash('success', 'mail verwijderd');

        return redirect()->route('mess');
    }

    /**
     * add new message
     *
     * @param AddMessageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(AddMessageRequest $request)
    {
        $data = $request->all();
        $data['user_id']= $this->user_id;
        $this->mess->create($data);

        return redirect()->route('mess');
    }

    /**
     * edit message
     *
     * @param EditMessageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(EditMessageRequest $request)
    {
        $this->mess->find($request->id)
            ->update($request->all());

        return redirect()->route('mess');
    }
}
