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
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($subject, $content, $to)
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->to = $to;
    }

    /**
     *      * sendMail to requested contact

     * @return \Illuminate\Http\JsonResponse
     */
    public function handle()
    {
        $to = $this->to;
        $subject = $this->subject;
        $content = $this->content;

        Mail::send('mails.send', ['title' => $subject, 'content' => $content],
            function ($message) use ($subject, $to)
        {
            $message->from('jonas.vanreeth@student.kdg.be', 'Jonas Van Reeth');
            $message->subject($subject);
            $message->to($to);
        });

        return response()->json(['message' => 'mail completed']);
    }
}
