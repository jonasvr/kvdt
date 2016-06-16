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
        // waarom steekt ge dit nog eens in lokale variablene als je class gloabals hebt die je overal kan gebruiken???
        $to = $this->to;
        $subject = $this->subject;
        $content = $this->content;
        $from = $this->from;
        $user = $this->user; 

        Mail::send('mails.send', ['title' => $subject, 'content' => $content],
            function ($message) use ($subject, $to,$from,$user)
        {
            $message->from($from, 'Jonas Van Reeth');
            $message->subject($subject);
            $message->to($to);
        });

        return response()->json(['message' => 'mail completed']);
    }
}
