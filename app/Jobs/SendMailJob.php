<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;


class SendMailJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $subject;
    protected $content;
    protected $to;
    protected $from;
    protected $user;

    /**
     * SendMailJob constructor.
     * @param $subject
     * @param $content
     * @param $to
     * @param $from
     * @param $user
     * 
     */
    public function __construct($subject, $content, $to,$from,$user)
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->to = $to;
        $this->from = $from;
        $user->user= $user;
    }

    /**
     *      * sendMail to requested contact

     * @return \Illuminate\Http\JsonResponse
     */
    public function handle()
    {
        Mail::send('mails.send', ['title' => $this->subject, 'content' => $this->content],
            function ($message)
        {
            $message->from($this->from, $this->user->name);
            $message->subject($this->subject);
            $message->to($this->to);
        });

        return response()->json(['message' => 'mail completed']);
    }
}
