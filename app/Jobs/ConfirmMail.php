<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;


class ConfirmMail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * ConfirmMail constructor.
     * @param $content
     * @param $to
     * @param $user
     */
    public function __construct($content,$to,$user)
    {
        $this->content = $content;
        $this->to = $to;
        $user->user= $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $to = $this->to;

        $content = " emergency has sended to :" . $to . "</br>
        with following message: </br>".$this->content;
         $user = $this->user;

        Mail::send('mails.send', [
            'title' => 'emergency sended',
            'content' => $content
        ],
            function ($message) use ($to,$user)
            {
                $message->to($user);
            });

        return response()->json(['message' => 'mail completed']);
    }
}
