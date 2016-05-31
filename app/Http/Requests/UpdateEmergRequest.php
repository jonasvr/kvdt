<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Emergencies;

class UpdateEmergRequest extends Request
{
    /**
     * @var Emergencies
     */
    protected $emerg;

    public function __construct(Emergencies $emerg)
    {
//        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->emerg = $emerg;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'alarm_id' => 'required',
            'contact_id' => 'required',
            'message_id' => 'required',
        ];
    }
}
