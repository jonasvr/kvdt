<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Mails;
use Session;
use App\Http\Requests\AddMailRequest;
use App\Http\Requests\EditMailRequest;


class MailController extends Controller
{
    protected $mails;

    /**
     * MailController constructor.
     * @param Mails $mails
     */
    public function __construct(Mails $mails)
    {
        $this->mails = $mails;
        parent::__construct();
    }

    /**
     * alle mails ophalen
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get()
    {
        $data=[
                'mails' => $this->mails->GetAll($this->user_id),
                'action'=> 'add',
        ];

        return view('contacts.mails',$data);
    }

    /**
     * pick up email data to edit
     *
     * @param $id
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEdit($id){
        $mail = $this->mails->find($id);
        if(!$mail)
        {
            return redirect()->route('mails')
                ->withErrors(['message'=>'foutieve id']);
        }
        $data=[
            'mails' => $this->mails->GetAll($this->user_id),
            'edit'  => $mail,
        ];

        return view('contacts.mails',$data);
    }

    /**
     * create new mail contact
     *
     * @param AddMailRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(AddMailRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = $this->user_id;
        $this->mails->create($data);

        return redirect()->route('mails');
    }

    /**
     * delete mail contact
     *
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function delete($id){
        $mail = $this->mails->find($id);
        if($mail==null)
        {
            $data=[
                    'mails' => $this->mails->GetAll($this->user_id),
            ];
            return view('contacts.mails',$data) //redirect met error?
                            ->withErrors(['message'=>'foutieve id']);
        }
        $mail->delete();
        Session::flash('success', 'mail verwijderd');

        return redirect()->route('mails');
    }


    /**
     * edit mail contact
     *
     * @param EditMailRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(EditMailRequest $request)
    {
        $this->mails
            ->find($request->id)
            ->update($request->all());

        return redirect()->route('mails');
    }
}
