<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Koten;

class AddKotRequest extends Request
{
    protected $koten;

    /**
     * AddKotRequest constructor.
     * @param Kotens $koten
     */
    public function __construct(Koten $koten)
    {
        $this->koten = $koten;
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
            'kot_id' => 'required|exists:kotens,kot_id',
        ];
    }
}
