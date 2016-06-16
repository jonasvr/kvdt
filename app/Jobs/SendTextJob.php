<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Textmagic;


class SendTextJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $text;
    protected $number;
    /**
     * SendTextJob constructor.
     * @param $text
     * @param $number
     */

    public function __construct($text, $number)
    {
       $this->text = $text;
        $this->number = $number;
    }

    /**
     * send texts/sms
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(){
        // same as u andere job + code styling
        $number = $this->number;
        $text = $this->text;
        $text =  strip_tags($text);

        Textmagic::trigger('messages','create', [
            'text'      => $text,
            'phones'    => $number,
        ]);

        return response()->json(['message' => 'text completed']);
    }
}
