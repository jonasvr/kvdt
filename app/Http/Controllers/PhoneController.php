<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use App\PhoneNumbers;
use Session;
use App\Http\Requests\AddNrsRequest;
use App\Http\Requests\EditNrsRequest;


class PhoneController extends Controller
{
    /**
     * @var PhoneNumbers
     */
    protected $nrs;

    /**
     * PhoneController constructor.
     * @param PhoneNumbers $nrs
     */
    public function __construct(PhoneNumbers $nrs)
    {
        parent::__construct();
        $this->nrs = $nrs;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get()
    {
        $data=[
            'nrs' => $this->nrs->GetAll($this->user_id),
        ];

        return view('contacts.numbers',$data);
    }

    /**
     * @param $id
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEdit($id)
    {
        $nr = $this->nrs->find($id);
        if(!$nr){

            return redirect()->route('numbers')
                ->withErrors(['message'=>'foutieve id']);
        }
        $data=[
            'nrs' => $this->nrs->GetAll($this->user_id),
            'edit' => $nr,
        ];

        return view('contacts.numbers',$data);
    }

    /**
     * @param AddNrsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(AddnrsRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = $this->user_id;
        $this->nrs->create($data);

        return redirect()->route('numbers');
    }

    /**
     * @param EditNrsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(EditNrsRequest $request)
    {
        $this->nrs
            ->find($request->id)
            ->update($request->all());

        return redirect()->route('numbers');
    }

    /**
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $nr = $this->nrs->find($id);
        if($nr==null){
            $data=[
                'nr' => $this->nrs->GetAll($this->user_id),
            ];

            return view('contacts.numbers',$data) //redirect met error?
            ->withErrors(['message'=>'foutieve id']);
        }

        $nr->delete();
        Session::flash('success', 'nr verwijderd');

        return redirect()->route('numbers');
    }
}
